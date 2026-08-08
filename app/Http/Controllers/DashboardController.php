<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use App\Models\Agent;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {

	public function __construct() {
        // $this->middleware(['auth']);
    }

	public function dashboard()
    {
        $orderQuery = Order::query();

        if (Auth::user()->user_type === 'agent') {
            $agentId = Agent::where('user_id', Auth::id())->value('agent_id');
            $orderQuery->where('assigned_agent_id', $agentId ?: '__no_agent__');
        }

        $newOrdersQuery = (clone $orderQuery)->where(function ($query) {
            $query->whereNull('order_status')
                ->orWhereIn(DB::raw('LOWER(order_status)'), ['new', 'pending', 'processing']);
        });

        $successfulOrdersQuery = (clone $orderQuery)->where(function ($query) {
            $query->whereIn(DB::raw('LOWER(order_status)'), ['confirmed', 'completed', 'success', 'successful'])
                ->orWhereIn(DB::raw('LOWER(delivery_status)'), ['delivered', 'completed', 'success', 'successful']);
        });

        $failedOrdersQuery = (clone $orderQuery)->where(function ($query) {
            $query->whereIn(DB::raw('LOWER(order_status)'), ['cancel', 'cancelled', 'failed', 'returned'])
                ->orWhereIn(DB::raw('LOWER(delivery_status)'), ['cancel', 'cancelled', 'failed', 'returned']);
        });

        $todayOrdersQuery = (clone $orderQuery)->whereDate('orders.created_at', today());
        $todaySuccessfulQuery = (clone $todayOrdersQuery)->where(function ($query) {
            $query->whereIn(DB::raw('LOWER(order_status)'), ['confirmed', 'completed', 'success', 'successful'])
                ->orWhereIn(DB::raw('LOWER(delivery_status)'), ['delivered', 'completed', 'success', 'successful']);
        });
        $todayFailedQuery = (clone $todayOrdersQuery)->where(function ($query) {
            $query->whereIn(DB::raw('LOWER(order_status)'), ['cancel', 'cancelled', 'failed', 'returned'])
                ->orWhereIn(DB::raw('LOWER(delivery_status)'), ['cancel', 'cancelled', 'failed', 'returned']);
        });
        $todayOnDeliveryQuery = (clone $todayOrdersQuery)->whereIn(
            DB::raw('LOWER(delivery_status)'),
            ['on delivery', 'on_delivery', 'in transit', 'in_transit', 'shipped', 'picked up', 'picked_up']
        );

        $todayOrderCount = (clone $todayOrdersQuery)->count();
        $todaySuccessfulCount = (clone $todaySuccessfulQuery)->count();
        $todayFailedCount = (clone $todayFailedQuery)->count();
        $todayPanel = [
            'total_sell' => (float) (clone $todaySuccessfulQuery)->sum('final_price'),
            'total_orders' => $todayOrderCount,
            'on_delivery' => (clone $todayOnDeliveryQuery)->count(),
            'successful_orders' => $todaySuccessfulCount,
            'new_clients' => Customer::whereDate('created_at', today())->count(),
            'success_rate' => $todayOrderCount > 0
                ? round(($todaySuccessfulCount / $todayOrderCount) * 100, 1)
                : 0,
            'failed_rate' => $todayOrderCount > 0
                ? round(($todayFailedCount / $todayOrderCount) * 100, 1)
                : 0,
        ];

        $dashboardYear = now()->year;
        $monthlyOrderRows = (clone $orderQuery)
            ->whereYear('orders.created_at', $dashboardYear)
            ->selectRaw('MONTH(orders.created_at) as month_number, COUNT(*) as order_count')
            ->groupByRaw('MONTH(orders.created_at)')
            ->pluck('order_count', 'month_number');

        $monthlyOrderStats = collect(range(1, 12))->map(function ($month) use ($monthlyOrderRows) {
            return [
                'label' => Carbon::create(null, $month, 1)->format('M'),
                'count' => (int) ($monthlyOrderRows[$month] ?? 0),
            ];
        });

        $yearOrderCount = (clone $orderQuery)->whereYear('orders.created_at', $dashboardYear)->count();
        $yearSuccessfulCount = (clone $successfulOrdersQuery)->whereYear('orders.created_at', $dashboardYear)->count();
        $yearFailedCount = (clone $failedOrdersQuery)->whereYear('orders.created_at', $dashboardYear)->count();
        $yearSuccessfulSales = (float) (clone $successfulOrdersQuery)
            ->whereYear('orders.created_at', $dashboardYear)
            ->sum('final_price');

        $yearOrderSummary = [
            'orders' => $yearOrderCount,
            'successful' => $yearSuccessfulCount,
            'failed' => $yearFailedCount,
            'sales' => $yearSuccessfulSales,
        ];

        $dashboardAgents = Agent::with('user')
            ->withCount('assignedOrders')
            ->where('status', 1)
            ->orderByDesc('assigned_orders_count')
            ->orderBy('first_name')
            ->limit(6)
            ->get();

        $todaySales = (clone $todayOrdersQuery)
            ->with('billingAddress')
            ->latest('orders.created_at')
            ->limit(7)
            ->get();

        $todaySalesTotal = (float) (clone $todaySuccessfulQuery)->sum('final_price');

        $currentAgentId = Auth::user()->user_type === 'agent'
            ? Agent::where('user_id', Auth::id())->value('agent_id')
            : null;

        $newOrderList = Order::with(['billingAddress', 'assignedAgent'])
            ->where(function ($query) {
                $query->whereNull('order_status')
                    ->orWhereIn(DB::raw('LOWER(order_status)'), ['new', 'pending', 'processing']);
            })
            ->when($currentAgentId, function ($query) use ($currentAgentId) {
                $query->where(function ($assignmentQuery) use ($currentAgentId) {
                    $assignmentQuery->whereNull('assigned_agent_id')
                        ->orWhere('assigned_agent_id', $currentAgentId);
                });
            })
            ->latest()
            ->limit(7)
            ->get();

        $metrics = [
            [
                'icon' => 'orders',
                'label' => 'Total Orders',
                'value' => (clone $orderQuery)->count(),
                'suffix' => '',
                'note' => 'All orders received',
                'theme' => 'mint',
            ],
            [
                'icon' => 'new',
                'label' => 'New Orders',
                'value' => (clone $newOrdersQuery)->count(),
                'suffix' => '',
                'note' => 'New, pending or processing',
                'theme' => 'blue',
            ],
            [
                'icon' => 'success',
                'label' => 'Successful Orders',
                'value' => (clone $successfulOrdersQuery)->count(),
                'suffix' => '',
                'note' => 'Confirmed or delivered',
                'theme' => 'violet',
            ],
            [
                'icon' => 'failed',
                'label' => 'Failed Orders',
                'value' => (clone $failedOrdersQuery)->count(),
                'suffix' => '',
                'note' => 'Cancelled, failed or returned',
                'theme' => 'rose',
            ],
            [
                'icon' => 'products',
                'label' => 'Total Products',
                'value' => Product::where('status', 1)->count(),
                'suffix' => '',
                'note' => 'Active store products',
                'theme' => 'blue',
            ],
            [
                'icon' => 'clients',
                'label' => 'Total Clients',
                'value' => Customer::count(),
                'suffix' => '',
                'note' => 'Registered clients',
                'theme' => 'mint',
            ],
            [
                'icon' => 'agents',
                'label' => 'Total Agents',
                'value' => Agent::where('status', 1)->count(),
                'suffix' => '',
                'note' => 'Active agents',
                'theme' => 'rose',
            ],
            [
                'icon' => 'sales',
                'label' => 'Total Sell',
                'value' => (clone $successfulOrdersQuery)->sum('final_price'),
                'suffix' => '৳',
                'note' => 'Successful order revenue',
                'theme' => 'violet',
                'money' => true,
            ],
        ];

        return view('dashboard', compact(
            'metrics',
            'todayPanel',
            'dashboardYear',
            'monthlyOrderStats',
            'yearOrderSummary',
            'dashboardAgents',
            'todaySales',
            'todaySalesTotal',
            'newOrderList',
            'currentAgentId'
        ));
    }

	function profile() {
		return view('single_form');
	}

}
