<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const REPORTS = [
        'sales' => 'Sales Report',
        'product-sales' => 'Product Sales',
        'customers' => 'Customer Report',
        'inventory' => 'Inventory Report',
        'tax' => 'Tax Report',
        'profit-loss' => 'Profit/Loss',
        'payments' => 'Payment Report',
        'best-selling-products' => 'Best Selling Products',
    ];

    public function show(Request $request, string $report): View
    {
        abort_unless($request->user()?->user_type === 'admin', 403);
        abort_unless(array_key_exists($report, self::REPORTS), 404);

        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $validated['end_date'] ?? now()->toDateString();
        [$columns, $rows, $summary, $note] = $this->buildReport($report, $startDate, $endDate);

        return view('reports.index', [
            'report' => $report,
            'title' => self::REPORTS[$report],
            'reports' => self::REPORTS,
            'columns' => $columns,
            'rows' => $rows,
            'summary' => $summary,
            'note' => $note,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function buildReport(string $report, string $startDate, string $endDate): array
    {
        return match ($report) {
            'sales' => $this->sales($startDate, $endDate),
            'product-sales' => $this->productSales($startDate, $endDate),
            'customers' => $this->customers($startDate, $endDate),
            'inventory' => $this->inventory($startDate, $endDate),
            'tax' => $this->tax($startDate, $endDate),
            'profit-loss' => $this->profitLoss($startDate, $endDate),
            'payments' => $this->payments($startDate, $endDate),
            'best-selling-products' => $this->bestSelling($startDate, $endDate),
        };
    }

    private function sales(string $start, string $end): array
    {
        $query = $this->salesOrders($start, $end);
        $totals = (clone $query)->selectRaw('COUNT(*) orders_count, COALESCE(SUM(total_price),0) gross_sales, COALESCE(SUM(discount),0) discounts, COALESCE(SUM(delivery_charge),0) delivery, COALESCE(SUM(final_price),0) net_sales')->first();
        $rows = $query->selectRaw('DATE(created_at) report_date, COUNT(*) orders_count, COALESCE(SUM(total_price),0) gross_sales, COALESCE(SUM(discount),0) discounts, COALESCE(SUM(delivery_charge),0) delivery, COALESCE(SUM(final_price),0) net_sales')
            ->groupByRaw('DATE(created_at)')->orderByDesc('report_date')->paginate(25)->withQueryString();

        return [
            ['report_date' => 'Date', 'orders_count' => 'Orders', 'gross_sales' => 'Gross Sales', 'discounts' => 'Discount', 'delivery' => 'Delivery', 'net_sales' => 'Net Sales'],
            $rows,
            ['Orders' => $totals->orders_count, 'Gross Sales' => $totals->gross_sales, 'Net Sales' => $totals->net_sales],
            null,
        ];
    }

    private function productSales(string $start, string $end): array
    {
        $query = $this->productSalesQuery($start, $end);
        $totals = (clone $query)->selectRaw('COALESCE(SUM(od.quantity),0) units, COALESCE(SUM(od.quantity * od.unit_price),0) revenue')->first();
        $rows = $query->selectRaw("p.product_code, p.name product_name, COALESCE(SUM(od.quantity),0) units, COALESCE(AVG(od.unit_price),0) average_price, COALESCE(SUM(od.quantity * od.unit_price),0) revenue")
            ->groupBy('p.id', 'p.product_code', 'p.name')->orderByDesc('revenue')->paginate(25)->withQueryString();

        return [
            ['product_code' => 'Code', 'product_name' => 'Product', 'units' => 'Units Sold', 'average_price' => 'Average Price', 'revenue' => 'Sales'],
            $rows,
            ['Products' => $rows->total(), 'Units Sold' => $totals->units, 'Sales' => $totals->revenue],
            null,
        ];
    }

    private function customers(string $start, string $end): array
    {
        $query = $this->dated(DB::table('orders as o'), 'o.created_at', $start, $end)
            ->whereRaw("LOWER(COALESCE(o.order_status, '')) != 'cancelled'")
            ->leftJoin('billing_address as ba', 'ba.id', '=', 'o.billing_address_id');
        $rows = $query->selectRaw("COALESCE(NULLIF(TRIM(CONCAT(COALESCE(ba.first_name,''), ' ', COALESCE(ba.last_name,''))),''), 'Guest') customer_name, COALESCE(ba.mobile, o.order_phone_number, '-') mobile, COALESCE(ba.email, '-') email, COUNT(o.id) orders_count, COALESCE(SUM(o.final_price),0) total_spent, MAX(o.created_at) last_order")
            ->groupByRaw("COALESCE(ba.mobile, o.order_phone_number, CONCAT('guest-', o.id)), ba.first_name, ba.last_name, ba.email")
            ->orderByDesc('total_spent')->paginate(25)->withQueryString();
        $totals = $this->salesOrders($start, $end)
            ->selectRaw('COUNT(DISTINCT COALESCE(order_phone_number, session_id, id)) customers_count, COUNT(*) orders_count, COALESCE(SUM(final_price),0) sales')->first();

        return [
            ['customer_name' => 'Customer', 'mobile' => 'Mobile', 'email' => 'Email', 'orders_count' => 'Orders', 'total_spent' => 'Total Spent', 'last_order' => 'Last Order'],
            $rows,
            ['Customers' => $totals->customers_count, 'Orders' => $totals->orders_count, 'Sales' => $totals->sales],
            null,
        ];
    }

    private function inventory(string $start, string $end): array
    {
        $query = $this->dated(DB::table('products as p'), 'p.created_at', $start, $end);
        $totalStock = 'COALESCE(p.stock_quantity,0)+COALESCE(p.xxs_stock,0)+COALESCE(p.xs_stock,0)+COALESCE(p.s_stock,0)+COALESCE(p.m_stock,0)+COALESCE(p.l_stock,0)+COALESCE(p.xl_stock,0)+COALESCE(p.xxl_stock,0)+COALESCE(p.xxxl_stock,0)+COALESCE(p.xxxxl_stock,0)';
        $totals = (clone $query)->selectRaw("COUNT(*) products_count, COALESCE(SUM($totalStock),0) units, COALESCE(SUM(($totalStock) * COALESCE(p.product_cost,0)),0) stock_value")->first();
        $rows = $query->selectRaw("p.product_code, p.name product_name, p.stock_status, $totalStock stock_quantity, COALESCE(p.product_cost,0) unit_cost, ($totalStock) * COALESCE(p.product_cost,0) stock_value")
            ->orderBy('p.name')->paginate(25)->withQueryString();

        return [
            ['product_code' => 'Code', 'product_name' => 'Product', 'stock_status' => 'Status', 'stock_quantity' => 'Stock', 'unit_cost' => 'Unit Cost', 'stock_value' => 'Stock Value'],
            $rows,
            ['Products Added' => $totals->products_count, 'Stock Units' => $totals->units, 'Stock Value' => $totals->stock_value],
            'The date filter applies to the product creation date; stock quantities show the current balance.',
        ];
    }

    private function tax(string $start, string $end): array
    {
        $query = $this->salesOrders($start, $end);
        $totals = (clone $query)->selectRaw('COUNT(*) orders_count, COALESCE(SUM(total_price),0) taxable_sales')->first();
        $rows = $query->selectRaw("COALESCE(custom_order_id, CONCAT('#', id)) invoice, DATE(created_at) report_date, COALESCE(total_price,0) taxable_amount, 0 tax_amount, COALESCE(final_price,0) total")
            ->orderByDesc('created_at')->paginate(25)->withQueryString();

        return [
            ['invoice' => 'Invoice', 'report_date' => 'Date', 'taxable_amount' => 'Taxable Sales', 'tax_amount' => 'Recorded Tax', 'total' => 'Order Total'],
            $rows,
            ['Orders' => $totals->orders_count, 'Taxable Sales' => $totals->taxable_sales, 'Recorded Tax' => 0],
            'The current orders table has no tax field, so recorded tax is shown as zero.',
        ];
    }

    private function profitLoss(string $start, string $end): array
    {
        $query = $this->productSalesQuery($start, $end);
        $select = 'p.product_code, p.name product_name, COALESCE(SUM(od.quantity * od.unit_price),0) revenue, COALESCE(SUM(od.quantity * COALESCE(p.product_cost,0)),0) cost, COALESCE(SUM(od.quantity * (od.unit_price - COALESCE(p.product_cost,0))),0) profit';
        $rows = $query->selectRaw($select)->groupBy('p.id', 'p.product_code', 'p.name')->orderByDesc('profit')->paginate(25)->withQueryString();
        $totals = $this->productSalesQuery($start, $end)->selectRaw('COALESCE(SUM(od.quantity * od.unit_price),0) revenue, COALESCE(SUM(od.quantity * COALESCE(p.product_cost,0)),0) cost, COALESCE(SUM(od.quantity * (od.unit_price - COALESCE(p.product_cost,0))),0) profit')->first();

        return [
            ['product_code' => 'Code', 'product_name' => 'Product', 'revenue' => 'Revenue', 'cost' => 'Product Cost', 'profit' => 'Profit/Loss'],
            $rows,
            ['Revenue' => $totals->revenue, 'Product Cost' => $totals->cost, 'Profit/Loss' => $totals->profit],
            'Profit/Loss is calculated from product sales minus product cost; operating expenses are not tracked.',
        ];
    }

    private function payments(string $start, string $end): array
    {
        $query = $this->dated(DB::table('orders'), 'created_at', $start, $end);
        $totals = (clone $query)->selectRaw('COUNT(*) orders_count, COALESCE(SUM(pay_amount),0) paid, COALESCE(SUM(GREATEST(COALESCE(final_price,0)-COALESCE(pay_amount,0),0)),0) due')->first();
        $rows = $query->selectRaw("COALESCE(custom_order_id, CONCAT('#', id)) invoice, DATE(created_at) report_date, COALESCE(payment_type, 'Not set') payment_method, COALESCE(payment_status, 'Not set') payment_status, COALESCE(final_price,0) order_total, COALESCE(pay_amount,0) paid, GREATEST(COALESCE(final_price,0)-COALESCE(pay_amount,0),0) due")
            ->orderByDesc('created_at')->paginate(25)->withQueryString();

        return [
            ['invoice' => 'Invoice', 'report_date' => 'Date', 'payment_method' => 'Method', 'payment_status' => 'Status', 'order_total' => 'Total', 'paid' => 'Paid', 'due' => 'Due'],
            $rows,
            ['Orders' => $totals->orders_count, 'Paid' => $totals->paid, 'Due' => $totals->due],
            null,
        ];
    }

    private function bestSelling(string $start, string $end): array
    {
        $query = $this->productSalesQuery($start, $end);
        $rows = $query->selectRaw('p.product_code, p.name product_name, COALESCE(SUM(od.quantity),0) units, COUNT(DISTINCT o.id) orders_count, COALESCE(SUM(od.quantity * od.unit_price),0) revenue')
            ->groupBy('p.id', 'p.product_code', 'p.name')->orderByDesc('units')->orderByDesc('revenue')->paginate(25)->withQueryString();

        return [
            ['product_code' => 'Code', 'product_name' => 'Product', 'units' => 'Units Sold', 'orders_count' => 'Orders', 'revenue' => 'Sales'],
            $rows,
            ['Products' => $rows->total(), 'Top Product Units' => $rows->first()?->units ?? 0, 'Top Product Sales' => $rows->first()?->revenue ?? 0],
            null,
        ];
    }

    private function productSalesQuery(string $start, string $end): Builder
    {
        return $this->dated(DB::table('order_details as od'), 'o.created_at', $start, $end)
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->join('products as p', 'p.id', '=', 'od.product_id')
            ->whereRaw("LOWER(COALESCE(o.order_status, '')) != 'cancelled'");
    }

    private function salesOrders(string $start, string $end): Builder
    {
        return $this->dated(DB::table('orders'), 'orders.created_at', $start, $end)
            ->whereRaw("LOWER(COALESCE(orders.order_status, '')) != 'cancelled'");
    }

    private function dated(Builder $query, string $column, string $start, string $end): Builder
    {
        return $query->whereBetween($column, [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
    }
}
