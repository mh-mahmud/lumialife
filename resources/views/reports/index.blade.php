@extends('layouts.master')

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="text-dark fw-bolder fs-3 my-1">{{ $title }}</h1>
        </div>
    </div>

    <div class="container-xxl py-4">
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="card mb-6">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.show', $report) }}" class="row g-4 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bolder">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-solid" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bolder">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-solid" required>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Filter Report</button>
                        <a href="{{ route('reports.show', $report) }}" class="btn btn-light">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-5 mb-6">
            @foreach ($summary as $label => $value)
                @php($isMoney = !in_array($label, ['Orders', 'Products', 'Products Added', 'Customers', 'Units Sold', 'Stock Units', 'Top Product Units']))
                <div class="col-md-4">
                    <div class="card h-100"><div class="card-body">
                        <div class="text-muted fw-bold mb-2">{{ $label }}</div>
                        <div class="fs-2 fw-bolder">{{ $isMoney ? '৳'.number_format((float) $value, 2) : number_format((float) $value) }}</div>
                    </div></div>
                </div>
            @endforeach
        </div>

        @if ($note)<div class="alert alert-info">{{ $note }}</div>@endif

        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ $title }}: {{ $startDate }} to {{ $endDate }}</h3></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-200 align-middle gy-4 mb-0">
                        <thead><tr class="fw-bolder text-muted bg-light">@foreach ($columns as $label)<th class="px-5">{{ $label }}</th>@endforeach</tr></thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    @foreach ($columns as $key => $label)
                                        @php($money = in_array($key, ['gross_sales','discounts','delivery','net_sales','average_price','revenue','total_spent','unit_cost','stock_value','taxable_amount','tax_amount','total','cost','profit','order_total','paid','due']))
                                        <td class="px-5">{{ $money ? '৳'.number_format((float) $row->{$key}, 2) : $row->{$key} }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ count($columns) }}" class="text-center text-muted py-10">No data found for the selected date range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($rows->hasPages())<div class="card-footer">{{ $rows->links() }}</div>@endif
        </div>
    </div>
@endsection
