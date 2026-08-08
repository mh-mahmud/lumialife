<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function getAllOrders()
    {
        $query = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->leftJoin('agents', 'orders.assigned_agent_id', '=', 'agents.agent_id')
            ->select('orders.id as lukaku', 'orders.*', 'billing_address.*',
                DB::raw("TRIM(CONCAT(COALESCE(agents.first_name, ''), ' ', COALESCE(agents.last_name, ''))) as assigned_staff"));

        $this->limitToAssignedStaff($query);

        $orders = $query
            ->orderBy('orders.id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));

        $this->attachOrderDetails($orders);

        return $orders;
    }

    public function getUnassignedOrders()
    {
        return $this->getScopedOrders(fn (Builder $query) => $query->whereNull('orders.assigned_agent_id'));
    }

    public function getOrdersForAgent(string $agentId)
    {
        return $this->getScopedOrders(
            fn (Builder $query) => $query->where('orders.assigned_agent_id', $agentId)
        );
    }

    private function getScopedOrders(callable $scope)
    {
        $query = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->leftJoin('agents', 'orders.assigned_agent_id', '=', 'agents.agent_id')
            ->select('orders.id as lukaku', 'orders.*', 'billing_address.*',
                DB::raw("TRIM(CONCAT(COALESCE(agents.first_name, ''), ' ', COALESCE(agents.last_name, ''))) as assigned_staff"));

        $scope($query);

        $orders = $query
            ->orderBy('orders.id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));

        $this->attachOrderDetails($orders);

        return $orders;
    }

    public function createOrder_backup($request)
    {

        $order = OrderInfo::create([
            'invoice_no' => 'INV-' . Str::upper(Str::random(8)),
            'customer_id' => $request->customer_id,
            'mobile_number' => $request->mobile_number,
            'area' => $request->area,
            'contact_address' => $request->contact_address,
            'sub_total' => $request->unit_price * $request->quantity,
            'order_tax' => 0,
            'shipping_charge' => 0,
            'discount' => 0,
            'payable_amount' => $request->unit_price * $request->quantity,
            'status' => 'New',
            'order_date' => now(),
        ]);


        OrderDetail::create([
            'order_id' => $order->order_id,
            'product_id' => $request->product_id,
            'product_code' => Product::find($request->product_id)->product_code,
            'product_name' => Product::find($request->product_id)->name,
            'product_color' => $request->product_color,
            'product_size' => $request->product_size,
            'unit_price' => $request->unit_price,
            'mprize' => $request->mprize,
            'quantity' => $request->quantity,
            'total_price' => $request->unit_price * $request->quantity,
        ]);
    }


    public function createOrder($request)
    {

        $order = OrderInfo::create([
            'invoice_no' => mt_rand(10000, 99999),
            'customer_id' => $request->customer_id,
            'mobile_number' => $request->mobile_number,
            'area' => $request->area,
            'contact_address' => $request->contact_address,
            //'sub_total' => $request->unit_price * $request->quantity, 
            'sub_total' =>  $request->sub_total,
            'order_tax' => 0,
            'shipping_charge' =>  $request->shipping_charge,
            'discount' => $request->discount,
            'payable_amount' => $request->payable_amount,
            'status' => 'New',
            'order_date' => now(),
        ]);

        //every product to the order details
        foreach ($request->products as $product) {
            OrderDetail::create([
                'order_id' => $order->order_id,
                'product_id' => $product['product_id'],
                'product_code' => Product::find($product['product_id'])->product_code,
                'product_name' => Product::find($product['product_id'])->name,
                'product_color' => $product['product_color'],
                'product_size' => $product['product_size'],
                'unit_price' => $product['unit_price'],
                'quantity' => $product['quantity'],
                'total_price' => $product['unit_price'] * $product['quantity'],
            ]);
        }
    }


    public function getOrder_backup($id)
    {
        return OrderInfo::join('customers', 'order_info.customer_id', '=', 'customers.id')
        ->select('order_info.*', 'customers.name as customer_name')
        ->where('order_info.order_id', $id)
        ->firstOrFail();
    }

    public function getOrder($id)
    {

        // $order = OrderInfo::join('customers', 'order_info.customer_id', '=', 'customers.id')
        // ->select('order_info.*')
        // ->where('order_info.order_id', $id)
        // ->firstOrFail();
        // $orderDetails = OrderDetail::where('order_id', $id)
        //     ->get();

        $order = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->select('orders.*', 'orders.id as lukaku', 'billing_address.*')
            ->where('orders.id', $id)
            ->first();
        $orderDetails = OrderDetail::with('product')->where('order_id', $id)->get();
        // dd($orderDetails);
        return [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ];
    }




    public function updateOrder($id, $data)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = $data['payment_status'];
        $order->payment_type = $data['payment_type'];
        $order->pay_amount = $data['pay_amount'];
        $order->delivery_note = $data['delivery_note'];
        $order->order_status = $data['order_status'];
        $order->cancel_reason = $data['cancel_reason'];
        $order->delivery_status = $data['delivery_status'];
        $order->delivery_date = $data['delivery_date'];
        $order->cancel_date = $data['cancel_date'];
        // $data['total_price'] = $data['unit_price'] * $data['quantity'];
        $order->update();
        return $order;
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);

        if (strcasecmp(trim((string) $order->order_status), 'Pending') !== 0) {
            throw new \RuntimeException('Only pending orders can be deleted.');
        }

        return DB::transaction(function () use ($order, $id) {
            OrderDetail::where('order_id', $id)->delete();
            return $order->delete();
        });
    }


    public function searchOrders($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = Order::join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
            ->leftJoin('agents', 'orders.assigned_agent_id', '=', 'agents.agent_id')
            ->select('orders.id as lukaku', 'orders.*', 'billing_address.*',
                DB::raw("TRIM(CONCAT(COALESCE(agents.first_name, ''), ' ', COALESCE(agents.last_name, ''))) as assigned_staff"));

        $this->limitToAssignedStaff($query);

        $orders = $query
            ->where(function ($query) use ($searchTerm) {
                $query->where('orders.custom_order_id', 'LIKE', "%$searchTerm%")
                    ->orWhere('orders.order_phone_number', 'LIKE', "%$searchTerm%")
                    ->orWhere('billing_address.first_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('billing_address.last_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('billing_address.mobile', 'LIKE', "%$searchTerm%");
            })
            ->orderBy('orders.id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));

        $this->attachOrderDetails($orders);

        return $orders;
    }

    private function limitToAssignedStaff(Builder $query): void
    {
        $user = Auth::user();

        if ($user && $user->user_type !== 'admin') {
            $query->where('agents.user_id', $user->id);
        }
    }

    private function attachOrderDetails($orders)
    {
        $orderIds = $orders->getCollection()->pluck('lukaku')->filter();
        $details = OrderDetail::with('product')->whereIn('order_id', $orderIds)->get()->groupBy('order_id');

        $orders->getCollection()->transform(function ($order) use ($details) {
            $order->setRelation('orderDetails', $details->get($order->lukaku, collect()));
            return $order;
        });
    }

}
