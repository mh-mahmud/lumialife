<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    private const REPORTS = [
        'orders' => ['title' => 'Order Report', 'description' => 'Review orders, customers, assigned staff and delivery details.'],
        'delivery' => ['title' => 'Delivery Report', 'description' => 'Track delivery progress, assigned staff and delivery dates.'],
        'sales' => ['title' => 'Sales Report', 'description' => 'Review sales performance for a selected period.'],
        'product-sales' => ['title' => 'Product Sales', 'description' => 'Analyze sales totals grouped by product.'],
        'customers' => ['title' => 'Customer Report', 'description' => 'Review customer acquisition and purchasing activity.'],
        'inventory' => ['title' => 'Inventory Report', 'description' => 'Monitor product stock and inventory availability.'],
        'profit-loss' => ['title' => 'Profit/Loss', 'description' => 'Compare revenue, costs, profit and loss.'],
        'payments' => ['title' => 'Payment Report', 'description' => 'Review payment methods and payment statuses.'],
        'best-selling-products' => ['title' => 'Best Selling Products', 'description' => 'Identify products with the highest sales volume.'],
    ];

    public function show(Request $request, string $report)
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);
        abort_unless(isset(self::REPORTS[$report]), 404);

        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'product_name' => ['nullable', 'string', 'max:191'],
            'agent_id' => ['nullable', 'string', 'exists:agents,agent_id'],
            'customer_search' => ['nullable', 'string', 'max:191'],
            'delivery_status' => ['nullable', 'string', 'max:100'],
            'payment_type' => ['nullable', 'string', 'max:100'],
            'payment_status' => ['nullable', 'string', 'max:100'],
            'order_status' => ['nullable', 'string', 'max:100'],
            'export' => ['nullable', 'in:xlsx'],
        ]);

        $filters['start_date'] = $filters['start_date'] ?? now()->startOfMonth()->toDateString();
        $filters['end_date'] = $filters['end_date'] ?? now()->toDateString();

        $periodStart = Carbon::parse($filters['start_date'])->startOfDay();
        $periodEnd = Carbon::parse($filters['end_date'])->endOfDay();
        $rows = null;
        $summary = [];

        if ($report === 'orders') {
            $ordersQuery = Order::with(['billingAddress', 'assignedAgent'])
                ->whereBetween('created_at', [$periodStart, $periodEnd]);

            if (!empty($filters['agent_id'])) {
                $ordersQuery->where('assigned_agent_id', $filters['agent_id']);
            }

            if (!empty($filters['customer_search'])) {
                $search = $filters['customer_search'];
                $ordersQuery->where(function ($query) use ($search) {
                    $query->where('order_phone_number', 'like', '%'.$search.'%')
                        ->orWhereHas('billingAddress', function ($billingQuery) use ($search) {
                            $billingQuery->where('mobile', 'like', '%'.$search.'%')
                                ->orWhere('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%')
                                ->orWhereRaw("TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, ''))) LIKE ?", ['%'.$search.'%']);
                        });
                });
            }

            $summary = [
                'orders' => (clone $ordersQuery)->count(),
                'total_price' => (float) (clone $ordersQuery)->sum('total_price'),
                'discount' => (float) (clone $ordersQuery)->sum('discount'),
                'delivery_charge' => (float) (clone $ordersQuery)->sum('delivery_charge'),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadOrders($ordersQuery->latest()->get());
            }

            $rows = $ordersQuery->latest()->paginate(20)->withQueryString();
        }

        if ($report === 'customers') {
            $customerQuery = Order::query()
                ->join('billing_address', 'orders.billing_address_id', '=', 'billing_address.id')
                ->whereBetween('orders.created_at', [$periodStart, $periodEnd]);

            if (!empty($filters['customer_search'])) {
                $search = $filters['customer_search'];
                $customerQuery->where(function ($query) use ($search) {
                    $query->where('billing_address.mobile', 'like', '%'.$search.'%')
                        ->orWhere('orders.order_phone_number', 'like', '%'.$search.'%')
                        ->orWhere('billing_address.first_name', 'like', '%'.$search.'%')
                        ->orWhere('billing_address.last_name', 'like', '%'.$search.'%')
                        ->orWhereRaw("TRIM(CONCAT(COALESCE(billing_address.first_name, ''), ' ', COALESCE(billing_address.last_name, ''))) LIKE ?", ['%'.$search.'%']);
                });
            }

            $customerQuery->selectRaw("COALESCE(NULLIF(billing_address.mobile, ''), orders.order_phone_number) as customer_phone")
                ->selectRaw("MAX(TRIM(CONCAT(COALESCE(billing_address.first_name, ''), ' ', COALESCE(billing_address.last_name, '')))) as customer_name")
                ->selectRaw('MAX(billing_address.email) as customer_email')
                ->selectRaw('COUNT(orders.id) as order_count')
                ->selectRaw('SUM(orders.final_price) as total_spent')
                ->selectRaw('MIN(orders.created_at) as first_order_at')
                ->selectRaw('MAX(orders.created_at) as last_order_at')
                ->groupBy('billing_address.mobile', 'orders.order_phone_number');

            $customerSummaryRows = (clone $customerQuery)->get();
            $summary = [
                'customers' => $customerSummaryRows->count(),
                'orders' => (int) $customerSummaryRows->sum('order_count'),
                'sales' => (float) $customerSummaryRows->sum('total_spent'),
                'avg_order' => (float) ($customerSummaryRows->sum('order_count') > 0
                    ? $customerSummaryRows->sum('total_spent') / $customerSummaryRows->sum('order_count')
                    : 0),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadCustomers($customerQuery->orderByDesc('total_spent')->get());
            }

            $rows = $customerQuery->orderByDesc('total_spent')->paginate(20)->withQueryString();
        }

        if ($report === 'delivery') {
            $deliveryQuery = Order::with(['billingAddress', 'assignedAgent'])
                ->whereBetween('created_at', [$periodStart, $periodEnd]);

            if (!empty($filters['delivery_status'])) {
                $deliveryQuery->where('delivery_status', $filters['delivery_status']);
            }

            if (!empty($filters['customer_search'])) {
                $search = $filters['customer_search'];
                $deliveryQuery->where(function ($query) use ($search) {
                    $query->where('order_phone_number', 'like', '%'.$search.'%')
                        ->orWhereHas('billingAddress', function ($billingQuery) use ($search) {
                            $billingQuery->where('mobile', 'like', '%'.$search.'%')
                                ->orWhere('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%');
                        });
                });
            }

            $summary = [
                'deliveries' => (clone $deliveryQuery)->count(),
                'delivered' => (clone $deliveryQuery)->where('delivery_status', 'Delivered')->count(),
                'pending' => (clone $deliveryQuery)->where(function ($query) {
                    $query->whereNull('delivery_status')->orWhere('delivery_status', 'like', '%pending%');
                })->count(),
                'amount' => (float) (clone $deliveryQuery)->sum('final_price'),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadDeliveries($deliveryQuery->latest()->get());
            }

            $rows = $deliveryQuery->latest()->paginate(20)->withQueryString();
        }

        if ($report === 'inventory') {
            $inventoryQuery = Product::with('category');

            if (!empty($filters['category_id'])) {
                $inventoryQuery->where('category_id', $filters['category_id']);
            }
            if (!empty($filters['product_name'])) {
                $inventoryQuery->where('name', 'like', '%'.$filters['product_name'].'%');
            }

            $summary = [
                'products' => (clone $inventoryQuery)->count(),
                'stock' => (int) (clone $inventoryQuery)->sum('stock_quantity'),
                'in_stock' => (clone $inventoryQuery)->where('stock_quantity', '>', 0)->count(),
                'out_of_stock' => (clone $inventoryQuery)->where('stock_quantity', '<=', 0)->count(),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadInventory($inventoryQuery->orderBy('name')->get());
            }

            $rows = $inventoryQuery->orderBy('name')->paginate(20)->withQueryString();
        }

        if ($report === 'payments') {
            $paymentQuery = Order::with('billingAddress')
                ->whereBetween('created_at', [$periodStart, $periodEnd]);

            if (!empty($filters['payment_type'])) {
                $paymentQuery->where('payment_type', $filters['payment_type']);
            }
            if (!empty($filters['payment_status'])) {
                $paymentQuery->where('payment_status', $filters['payment_status']);
            }

            $payable = (float) (clone $paymentQuery)->sum('final_price');
            $paid = (float) (clone $paymentQuery)->sum('pay_amount');
            $summary = [
                'payments' => (clone $paymentQuery)->count(),
                'payable' => $payable,
                'paid' => $paid,
                'due' => max(0, $payable - $paid),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadPayments($paymentQuery->latest()->get());
            }

            $rows = $paymentQuery->latest()->paginate(20)->withQueryString();
        }

        if ($report === 'profit-loss') {
            $profitQuery = Order::with('billingAddress')
                ->whereBetween('orders.created_at', [$periodStart, $periodEnd])
                ->select('orders.*')
                ->selectSub(function ($query) {
                    $query->from('order_details')
                        ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
                        ->whereColumn('order_details.order_id', 'orders.id')
                        ->selectRaw('COALESCE(SUM(order_details.quantity * COALESCE(products.product_cost, 0)), 0)');
                }, 'cost_total');

            if (!empty($filters['order_status'])) {
                $profitQuery->where('orders.order_status', $filters['order_status']);
            }
            if (!empty($filters['customer_search'])) {
                $search = $filters['customer_search'];
                $profitQuery->where(function ($query) use ($search) {
                    $query->where('orders.custom_order_id', 'like', '%'.$search.'%')
                        ->orWhere('orders.order_phone_number', 'like', '%'.$search.'%')
                        ->orWhereHas('billingAddress', function ($billingQuery) use ($search) {
                            $billingQuery->where('mobile', 'like', '%'.$search.'%')
                                ->orWhere('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%');
                        });
                });
            }

            $profitSummaryRows = (clone $profitQuery)->get();
            $revenue = (float) $profitSummaryRows->sum('final_price');
            $cost = (float) $profitSummaryRows->sum('cost_total');
            $profit = $revenue - $cost;
            $summary = [
                'revenue' => $revenue,
                'cogs' => $cost,
                'profit' => $profit,
                'margin' => $revenue > 0 ? ($profit / $revenue) * 100 : 0,
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadProfitLoss($profitQuery->latest('orders.created_at')->get());
            }

            $rows = $profitQuery->latest('orders.created_at')->paginate(20)->withQueryString();
        }

        if ($report === 'sales') {
            $salesQuery = Order::with(['billingAddress', 'orderDetails.product'])
                ->whereBetween('created_at', [$periodStart, $periodEnd]);

            $summary = [
                'orders' => (clone $salesQuery)->count(),
                'quantity' => (int) OrderDetail::whereHas('order', fn ($query) =>
                    $query->whereBetween('created_at', [$periodStart, $periodEnd])
                )->sum('quantity'),
                'sales' => (float) (clone $salesQuery)->sum('final_price'),
                'paid' => (float) (clone $salesQuery)->sum('pay_amount'),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadSales($salesQuery->latest()->get());
            }

            $rows = $salesQuery->latest()->paginate(20)->withQueryString();
        }

        if ($report === 'product-sales') {
            $productSalesQuery = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->whereBetween('orders.created_at', [$periodStart, $periodEnd])
                ->select([
                    'order_details.product_id',
                    DB::raw("COALESCE(products.name, CONCAT('Product #', order_details.product_id)) as product_name"),
                    DB::raw("COALESCE(categories.category_name, 'Uncategorized') as category_name"),
                    DB::raw("COALESCE(brands.brand_name, 'No Brand') as brand_name"),
                    'products.status as product_status',
                    'products.created_at as product_created_at',
                    'products.img_path as product_image',
                    'order_details.product_color',
                    'order_details.product_size',
                    DB::raw('COUNT(DISTINCT order_details.order_id) as order_count'),
                    DB::raw('SUM(order_details.quantity) as units_sold'),
                    DB::raw('SUM(order_details.quantity * order_details.unit_price) as sales_total'),
                ])
                ->groupBy(
                    'order_details.product_id',
                    'products.name',
                    'categories.category_name',
                    'brands.brand_name',
                    'products.status',
                    'products.created_at',
                    'products.img_path',
                    'order_details.product_color',
                    'order_details.product_size'
                );

            if (!empty($filters['category_id'])) {
                $productSalesQuery->where('products.category_id', $filters['category_id']);
            }

            if (!empty($filters['product_name'])) {
                $productSalesQuery->where('products.name', 'like', '%'.$filters['product_name'].'%');
            }

            $summaryRows = (clone $productSalesQuery)->get();
            $summary = [
                'products' => $summaryRows->count(),
                'orders' => (int) $summaryRows->sum('order_count'),
                'quantity' => (int) $summaryRows->sum('units_sold'),
                'sales' => (float) $summaryRows->sum('sales_total'),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                $products = Product::with(['productColors:id,name', 'productSizes:id,name'])
                    ->whereIn('id', $summaryRows->pluck('product_id')->filter()->unique())
                    ->orderBy('name')
                    ->get();

                return $this->downloadProductSales($products);
            }

            $rows = $productSalesQuery
                ->orderByDesc('sales_total')
                ->paginate(20)
                ->withQueryString();
        }

        if ($report === 'best-selling-products') {
            $bestSellingQuery = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->whereBetween('orders.created_at', [$periodStart, $periodEnd])
                ->select([
                    'order_details.product_id',
                    'products.name as product_name',
                    DB::raw("COALESCE(categories.category_name, 'Uncategorized') as category_name"),
                    DB::raw("COALESCE(brands.brand_name, 'No Brand') as brand_name"),
                    'products.status as product_status',
                    'products.created_at as product_created_at',
                    'products.img_path as product_image',
                    DB::raw("'' as product_color"),
                    DB::raw("'' as product_size"),
                    DB::raw('COUNT(DISTINCT order_details.order_id) as order_count'),
                    DB::raw('SUM(order_details.quantity) as units_sold'),
                    DB::raw('SUM(order_details.quantity * order_details.unit_price) as sales_total'),
                ])
                ->groupBy('order_details.product_id', 'products.name', 'categories.category_name', 'brands.brand_name', 'products.status', 'products.created_at', 'products.img_path');

            if (!empty($filters['category_id'])) {
                $bestSellingQuery->where('products.category_id', $filters['category_id']);
            }
            if (!empty($filters['product_name'])) {
                $bestSellingQuery->where('products.name', 'like', '%'.$filters['product_name'].'%');
            }

            $bestRows = (clone $bestSellingQuery)->get();
            $summary = [
                'products' => $bestRows->count(),
                'orders' => (int) $bestRows->sum('order_count'),
                'quantity' => (int) $bestRows->sum('units_sold'),
                'sales' => (float) $bestRows->sum('sales_total'),
            ];

            if (($filters['export'] ?? null) === 'xlsx') {
                return $this->downloadBestSelling($bestSellingQuery->orderByDesc('units_sold')->get());
            }

            $rows = $bestSellingQuery->orderByDesc('units_sold')->paginate(20)->withQueryString();
        }

        return view('reports.show', [
            'reportKey' => $report,
            'report' => self::REPORTS[$report],
            'filters' => $filters,
            'rows' => $rows,
            'summary' => $summary,
            'categories' => in_array($report, ['product-sales', 'best-selling-products', 'inventory'], true)
                ? Category::orderBy('category_name')->get(['id', 'category_name'])
                : collect(),
            'agents' => $report === 'orders'
                ? Agent::orderBy('first_name')->orderBy('last_name')->get(['agent_id', 'first_name', 'last_name'])
                : collect(),
        ]);
    }

    private function downloadOrders($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Order Report');
        $sheet->fromArray([
            'Order ID', 'Agent', 'Customer Name', 'Phone', 'Total Price', 'Final Price',
            'Discount', 'Delivery Charge', 'Coupon', 'Order Status', 'Delivery Status',
            'Order Note', 'Delivery Note', 'Possible Delivery Date', 'Delivery Date',
        ], null, 'A1');

        $line = 2;
        foreach ($rows as $order) {
            $sheet->fromArray([
                $order->custom_order_id ?: $order->id,
                $this->agentName($order),
                $this->customerName($order),
                optional($order->billingAddress)->mobile ?: $order->order_phone_number,
                (float) $order->total_price,
                (float) $order->final_price,
                (float) $order->discount,
                (float) $order->delivery_charge,
                $order->coupon ?: '',
                $order->order_status ?: '',
                $order->delivery_status ?: '',
                $order->order_note ?: '',
                $order->delivery_note ?: '',
                $this->formatReportDate($order->possible_delivery_date),
                $this->formatReportDate($order->delivery_date),
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:O1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:O'.max(1, $line - 1));
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'order-report-'.now()->format('Y-m-d-His').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function downloadSales($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sales Report');
        $sheet->fromArray([
            'Order ID', 'Date', 'Customer Name', 'Phone', 'Products',
            'Order Status', 'Payment Status', 'Total',
        ], null, 'A1');

        $line = 2;
        foreach ($rows as $order) {
            $products = $order->orderDetails->map(function ($detail) {
                $name = optional($detail->product)->name ?: 'Product unavailable';
                $variant = collect([
                    $detail->product_color ? 'Color: '.$detail->product_color : null,
                    $detail->product_size ? 'Size: '.$detail->product_size : null,
                ])->filter()->implode(', ');

                return $name.' × '.(int) $detail->quantity.($variant ? ' ('.$variant.')' : '');
            })->implode('; ');

            $sheet->fromArray([
                $order->custom_order_id ?: $order->id,
                optional($order->created_at)->format('Y-m-d H:i:s') ?: '',
                $this->customerName($order),
                optional($order->billingAddress)->mobile ?: $order->order_phone_number,
                $products,
                $order->delivery_status ?: ($order->order_status ?: 'Pending'),
                $order->payment_status ?: 'Not Paid',
                (float) $order->final_price,
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:H'.max(1, $line - 1));
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'sales-report-'.now()->format('Y-m-d-His').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function downloadCustomers($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Report');
        $sheet->fromArray([
            'Customer Name', 'Phone', 'Email', 'Orders', 'Total Spent',
            'Customer Type', 'First Order', 'Last Order',
        ], null, 'A1');

        $line = 2;
        foreach ($rows as $row) {
            $sheet->fromArray([
                $row->customer_name ?: 'Guest Customer',
                $row->customer_phone ?: '',
                $row->customer_email ?: '',
                (int) $row->order_count,
                (float) $row->total_spent,
                (int) $row->order_count > 1 ? 'Repeat Customer' : 'New Customer',
                $this->formatReportDate($row->first_order_at),
                $this->formatReportDate($row->last_order_at),
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:H'.max(1, $line - 1));
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'customer-report-'.now()->format('Y-m-d-His').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function downloadDeliveries($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Delivery Report');
        $sheet->fromArray(['Order ID', 'Customer', 'Phone', 'Agent', 'Order Status', 'Delivery Status', 'Amount', 'Possible Delivery Date', 'Delivery Date', 'Delivery Note'], null, 'A1');
        $line = 2;
        foreach ($rows as $order) {
            $sheet->fromArray([
                $order->custom_order_id ?: $order->id, $this->customerName($order),
                optional($order->billingAddress)->mobile ?: $order->order_phone_number,
                $this->agentName($order), $order->order_status ?: 'Pending',
                $order->delivery_status ?: 'Pending', (float) $order->final_price,
                $this->formatReportDate($order->possible_delivery_date),
                $this->formatReportDate($order->delivery_date), $order->delivery_note ?: '',
            ], null, 'A'.$line++);
        }
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:J'.max(1, $line - 1));
        foreach (range('A', 'J') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->freezePane('A2');
        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'delivery-report-'.now()->format('Y-m-d-His').'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function downloadBestSelling($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Best Selling Products');
        $sheet->fromArray(['Rank', 'Product Name', 'Category', 'Brand', 'Orders', 'Units Sold', 'Sales Total', 'Status', 'Created At'], null, 'A1');
        $line = 2;
        foreach ($rows as $index => $row) {
            $sheet->fromArray([
                $index + 1, $row->product_name, $row->category_name, $row->brand_name,
                (int) $row->order_count, (int) $row->units_sold, (float) $row->sales_total,
                (int) $row->product_status === 1 ? 'Active' : 'Inactive', $this->formatReportDate($row->product_created_at),
            ], null, 'A'.$line++);
        }
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:I'.max(1, $line - 1));
        foreach (range('A', 'I') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->freezePane('A2');
        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'best-selling-products-'.now()->format('Y-m-d-His').'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function downloadInventory($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventory Report');
        $sheet->fromArray(['Product Name', 'SKU', 'Stock', 'Price', 'Stock Status'], null, 'A1');

        $line = 2;
        foreach ($rows as $product) {
            $sheet->fromArray([
                $product->name,
                $product->product_code ?: '',
                (float) $product->stock_quantity,
                (float) $product->product_value,
                (float) $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock',
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:E'.max(1, $line - 1));
        foreach (range('A', 'E') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'inventory-report-'.now()->format('Y-m-d-His').'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function downloadPayments($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Payment Report');
        $sheet->fromArray(['Order ID', 'Date', 'Customer', 'Phone', 'Payment Method', 'Payment Status', 'Payable', 'Paid', 'Due'], null, 'A1');

        $line = 2;
        foreach ($rows as $order) {
            $payable = (float) $order->final_price;
            $paid = (float) $order->pay_amount;
            $sheet->fromArray([
                $order->custom_order_id ?: $order->id,
                optional($order->created_at)->format('Y-m-d H:i:s') ?: '',
                $this->customerName($order),
                optional($order->billingAddress)->mobile ?: $order->order_phone_number,
                $order->payment_type ?: 'Cash on Delivery',
                $order->payment_status ?: 'Not Paid',
                $payable, $paid, max(0, $payable - $paid),
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:I'.max(1, $line - 1));
        foreach (range('A', 'I') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'payment-report-'.now()->format('Y-m-d-His').'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function downloadProfitLoss($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Profit Loss');
        $sheet->fromArray(['Order ID', 'Date', 'Customer', 'Order Status', 'Revenue', 'COGS', 'Profit', 'Profit Margin'], null, 'A1');

        $line = 2;
        foreach ($rows as $order) {
            $revenue = (float) $order->final_price;
            $cost = (float) $order->cost_total;
            $profit = $revenue - $cost;
            $sheet->fromArray([
                $order->custom_order_id ?: $order->id,
                optional($order->created_at)->format('Y-m-d H:i:s') ?: '',
                $this->customerName($order),
                $order->order_status ?: 'Pending',
                $revenue, $cost, $profit,
                $revenue > 0 ? round(($profit / $revenue) * 100, 2).'%' : '0%',
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:H'.max(1, $line - 1));
        foreach (range('A', 'H') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->freezePane('A2');

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'profit-loss-report-'.now()->format('Y-m-d-His').'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function agentName(Order $order): string
    {
        return trim(optional($order->assignedAgent)->first_name.' '.optional($order->assignedAgent)->last_name) ?: 'Unassigned';
    }

    private function customerName(Order $order): string
    {
        return trim(optional($order->billingAddress)->first_name.' '.optional($order->billingAddress)->last_name) ?: 'Guest Customer';
    }

    private function formatReportDate($date): string
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : '';
    }

    private function downloadProductSales($rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Product Sales');
        $sheet->fromArray([
            'Product Name', 'Price', 'Product Status', 'Stock Status',
            'Stock Quantity', 'Available Sizes', 'Available Colors', 'Created At',
        ], null, 'A1');

        $line = 2;
        foreach ($rows as $product) {
            $sheet->fromArray([
                $product->name,
                (float) $product->product_value,
                (int) $product->status === 1 ? 'Active' : 'Inactive',
                (float) $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock',
                (float) $product->stock_quantity,
                $product->productSizes->pluck('name')->implode(', '),
                $product->productColors->pluck('name')->implode(', '),
                optional($product->created_at)->format('Y-m-d H:i:s') ?: '',
            ], null, 'A'.$line++);
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:H'.max(1, $line - 1));
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        $fileName = 'product-sales-'.now()->format('Y-m-d-His').'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
