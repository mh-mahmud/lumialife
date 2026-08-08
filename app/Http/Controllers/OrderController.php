<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\BillingAddress;
use App\Models\Agent;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $isAgent = Auth::user()->user_type === 'agent';
        $orders = $isAgent
            ? $this->orderService->getUnassignedOrders()
            : $this->orderService->getAllOrders();
        $agents = Agent::where('status', 1)->orderBy('first_name')->orderBy('last_name')->get();
        $pageMode = $isAgent ? 'unassigned' : 'all';
        return view('orders.index', compact('orders', 'agents', 'pageMode'));
    }

    public function myOrders()
    {
        abort_unless(Auth::user()->user_type === 'agent', 403);

        $agent = $this->currentAgent();
        $orders = $this->orderService->getOrdersForAgent($agent->agent_id);
        $agents = collect();
        $pageMode = 'mine';

        return view('orders.index', compact('orders', 'agents', 'pageMode'));
    }

    public function claimOrders(Request $request)
    {
        abort_unless(Auth::user()->user_type === 'agent', 403);

        $validated = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
            'return_to' => ['nullable', 'in:dashboard,my-orders'],
        ], ['order_ids.required' => 'Please select at least one order.']);

        $agent = $this->currentAgent();
        $updated = Order::whereIn('id', $validated['order_ids'])
            ->whereNull('assigned_agent_id')
            ->update(['assigned_agent_id' => $agent->agent_id]);

        return redirect()->route(($validated['return_to'] ?? null) === 'dashboard' ? 'dashboard' : 'agent-my-orders')
            ->with('success', $updated.' order(s) assigned to you successfully.');
    }

    public function create()
    {
        
        $products = Product::all();
        //$products = Product::select('id', 'name', 'description', 'product_value')->get();
        $customers = Customer::all();

        return view('orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'mobile_number' => 'required|string',
            'area' => 'required|string',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.product_color' => 'nullable|string',
            'products.*.product_size' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $this->orderService->createOrder($request);
        Helper::storeLog("Order created successfully", "Order", "Create Order", null,null);
        return redirect()->route('orders-index')->with('success', 'Order created successfully.');
    }

    public function show_backup($id)
    {
        $order = $this->orderService->getOrder($id);
        return view('orders.show', compact('order'));
    }

    public function show($id)
    {
        $this->ensureAgentCanManageOrder($id);
        $data = $this->orderService->getOrder($id);
        $products = Product::orderBy('name')->get(['id', 'name', 'product_value', 'discount_price', 'product_code', 'img_path']);
        return view('orders.show', [
            'order' => $data['order'],
            'order_id' => $id,
            'orderDetails' => $data['orderDetails'],
            'products' => $products,
        ]);
    }

    public function invoice($id)
    {
        $this->ensureAgentCanManageOrder($id);
        $data = $this->orderService->getOrder($id);

        abort_if(! $data['order'], 404);

        return view('orders.invoice', [
            'order' => $data['order'],
            'orderDetails' => $data['orderDetails'],
        ]);
    }

    public function addOrderItem(Request $request, $id)
    {
        $this->ensureAgentCanManageOrder($id);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:9999',
        ]);

        $order = Order::findOrFail($id);
        $product = Product::findOrFail($validated['product_id']);
        $unitPrice = (float) $product->discount_price > 0
            ? (float) $product->discount_price
            : (float) $product->product_value;

        DB::transaction(function () use ($order, $product, $unitPrice, $validated) {
            $item = OrderDetail::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($item) {
                $item->quantity += $validated['quantity'];
                $item->unit_price = $unitPrice;
                $item->total = $item->quantity * $unitPrice;
                $item->save();
            } else {
                OrderDetail::create([
                    'user_id' => $order->user_id,
                    'session_id' => $order->session_id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'total' => $validated['quantity'] * $unitPrice,
                ]);
            }

            $this->recalculateOrderTotals($order);
        });

        return $this->orderItemsJson($order, 'Product added to the order successfully.');
    }

    public function updateOrderItem(Request $request, $id, $detailId)
    {
        $this->ensureAgentCanManageOrder($id);
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:9999',
        ]);

        $order = Order::findOrFail($id);
        $item = OrderDetail::where('order_id', $order->id)->findOrFail($detailId);

        DB::transaction(function () use ($order, $item, $validated) {
            $item->quantity = $validated['quantity'];
            $item->total = $item->quantity * $item->unit_price;
            $item->save();
            $this->recalculateOrderTotals($order);
        });

        return $this->orderItemsJson($order, 'Product quantity updated successfully.');
    }

    public function deleteOrderItem($id, $detailId)
    {
        $this->ensureAgentCanManageOrder($id);
        $order = Order::findOrFail($id);
        $item = OrderDetail::where('order_id', $order->id)->findOrFail($detailId);

        DB::transaction(function () use ($order, $item) {
            $item->delete();
            $this->recalculateOrderTotals($order);
        });

        return $this->orderItemsJson($order, 'Product removed from the order successfully.');
    }

    private function recalculateOrderTotals(Order $order): void
    {
        $subtotal = (float) OrderDetail::where('order_id', $order->id)
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as subtotal')
            ->value('subtotal');
        $discountPercent = max(0, min(100, (float) $order->discount));
        $discountAmount = ($subtotal * $discountPercent) / 100;

        $order->total_price = $subtotal;
        $order->final_price = max(0, $subtotal - $discountAmount + (float) $order->delivery_charge);
        $order->save();
    }

    private function orderItemsJson(Order $order, string $message)
    {
        $order->refresh();
        $items = OrderDetail::with('product')->where('order_id', $order->id)->get();
        $subtotal = (float) $items->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_price);
        $shipping = (float) $order->delivery_charge;
        $payable = (float) $order->final_price;
        $discount = max(0, $subtotal + $shipping - $payable);
        $paid = (float) $order->pay_amount;
        $due = max(0, $payable - $paid);

        return response()->json([
            'message' => $message,
            'rows_html' => view('orders.partials.item-rows', ['orderDetails' => $items])->render(),
            'count' => $items->count(),
            'totals' => compact('subtotal', 'discount', 'payable', 'due'),
        ]);
    }


    public function edit($id)
    {
        $this->ensureAgentCanManageOrder($id);
        $order = $this->orderService->getOrder($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureAgentCanManageOrder($id);
        $this->orderService->updateOrder($id, $request);
        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    public function updateCustomerDelivery(Request $request, $id)
    {
        $this->ensureAgentCanManageOrder($id);
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'mobile' => 'required|string|max:30',
            'company_name' => 'nullable|string|max:150',
            'shipping_address' => 'required|string|max:500',
            'shipping_address_2' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:30',
            'order_note' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($id, $validated) {
            $order = Order::findOrFail($id);
            $billingAddress = BillingAddress::findOrFail($order->billing_address_id);
            $billingAddress->update(collect($validated)->except('order_note')->all());

            $order->order_phone_number = $validated['mobile'];
            $order->order_note = $validated['order_note'] ?? null;
            $order->save();
        });

        return redirect()->back()->with('success', 'Customer & delivery information updated successfully.');
    }

    public function destroy($id)
    {
        $this->ensureAgentCanManageOrder($id);
        try {
            $this->orderService->deleteOrder($id);
            return redirect()->route('orders-index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
           return redirect()->back()->with('error', $e->getMessage());
        }

    }

    // searech for invoice

    public function search(Request $request)
    {
        $searchTerm = trim($request->input('search'));
        if (empty($searchTerm)) {
            return redirect()->route('orders-index')->with('error', 'Search field cannot be blank.');
        }
        $orders = $this->orderService->searchOrders($request);
        $agents = Agent::where('status', 1)->orderBy('first_name')->orderBy('last_name')->get();
        return view('orders.index', compact('orders', 'agents'));
    }

    public function assignAgent(Request $request)
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,agent_id',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id',
        ], [
            'order_ids.required' => 'Please select at least one order.',
        ]);

        $updated = Order::whereIn('id', $validated['order_ids'])
            ->update(['assigned_agent_id' => $validated['agent_id']]);

        return redirect()->back()->with('success', $updated.' order(s) assigned successfully.');
    }

    private function currentAgent(): Agent
    {
        $agent = Agent::where('user_id', Auth::id())->where('status', 1)->first();
        abort_unless($agent, 403, 'No active agent profile is linked to this account.');

        return $agent;
    }

    private function ensureAgentCanManageOrder($orderId): void
    {
        if (Auth::user()->user_type !== 'agent') {
            return;
        }

        $agent = $this->currentAgent();
        abort_unless(
            Order::whereKey($orderId)->where('assigned_agent_id', $agent->agent_id)->exists(),
            403,
            'This order is not assigned to you.'
        );
    }

    
}
