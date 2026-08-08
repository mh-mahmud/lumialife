@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard-static.css') }}?v={{ filemtime(public_path('assets/css/admin-dashboard-static.css')) }}">

<div class="lumia-dashboard">
    @if (session('success'))
        <script>Swal.fire({icon:'success',title:'Success',text:@json(session('success')),showConfirmButton:false,timer:2000});</script>
    @endif
    @if (session('error'))
        <script>Swal.fire({icon:'error',title:'Error',text:@json(session('error')),showConfirmButton:false,timer:2000});</script>
    @endif

    <header class="dashboard-title">
        <div>
            <span>OVERVIEW</span>
            <h1>Business Dashboard</h1>
        </div>
        <time datetime="{{ now()->toDateString() }}">{{ now()->format('d M Y') }}</time>
    </header>

    <div class="metric-grid dashboard-metric-grid">
        @foreach($metrics as $metric)
            <article class="metric-card {{ $metric['theme'] }}">
                <div class="metric-head">
                    <div class="metric-name">
                        <span class="metric-icon" aria-hidden="true">
                            @switch($metric['icon'])
                                @case('orders')
                                    <svg viewBox="0 0 24 24"><path d="M5 3h14v18H5zM8 8h8M8 12h8M8 16h5"/></svg>
                                    @break
                                @case('new')
                                    <svg viewBox="0 0 24 24"><path d="M12 3v18M3 12h18"/></svg>
                                    @break
                                @case('success')
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg>
                                    @break
                                @case('failed')
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6"/></svg>
                                    @break
                                @case('products')
                                    <svg viewBox="0 0 24 24"><path d="m4 7 8-4 8 4-8 4zM4 7v10l8 4 8-4V7M12 11v10"/></svg>
                                    @break
                                @case('clients')
                                    <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2"/><path d="M3 20v-2a6 6 0 0 1 12 0v2m1-6a5 5 0 0 1 5 5v1"/></svg>
                                    @break
                                @case('agents')
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M6.5 20v-2.5a5.5 5.5 0 0 1 11 0V20M4 10v4m16-4v4"/></svg>
                                    @break
                                @default
                                    <svg viewBox="0 0 24 24"><path d="M4 18V9m5 9V5m5 13v-7m5 7V3"/></svg>
                            @endswitch
                        </span>
                        <span>{{ $metric['label'] }}</span>
                    </div>
                    <span class="metric-live">LIVE</span>
                </div>

                <div class="metric-value">
                    @if(!empty($metric['money']))
                        <small class="metric-currency">{{ $metric['suffix'] }}</small>{{ number_format((float) $metric['value'], 2) }}
                    @else
                        {{ number_format((int) $metric['value']) }}<small>{{ $metric['suffix'] }}</small>
                    @endif
                </div>
                <div class="metric-foot"><b>↗</b> {{ $metric['note'] }}<i></i>updated now</div>
            </article>
        @endforeach
    </div>

    <section class="dash-card geography">
        <header class="card-heading">
            <div><span class="eyebrow">TODAY'S PERFORMANCE</span><h2>Today Sell Panel</h2></div>
            <a href="{{ route('orders-index') }}">View orders <b>→</b></a>
        </header>
        <div class="country-grid">
            @foreach ([
                ['Total Sell', '৳'.number_format($todayPanel['total_sell'], 2), 'Today revenue', 'violet'],
                ['Total Orders', number_format($todayPanel['total_orders']), 'Received today', 'green'],
                ['On Delivery', number_format($todayPanel['on_delivery']), 'In delivery process', 'cyan'],
                ['Success Orders', number_format($todayPanel['successful_orders']), 'Completed today', 'slate'],
            ] as $todayStat)
            <div class="country">
                <div class="country-name"><i class="{{ $todayStat[3] }}"></i>{{ $todayStat[0] }}</div>
                <strong>{{ $todayStat[1] }} <small>{{ $todayStat[2] }}</small></strong>
                <div class="country-bar"><span class="{{ $todayStat[3] }}" style="width:100%"></span></div>
            </div>
            @endforeach
        </div>
        <div class="rings">
            @foreach ([
                [$todayPanel['new_clients'], 'New Clients', 'registered today', 'red', $todayPanel['new_clients'] > 0 ? 100 : 0, ''],
                [$todayPanel['success_rate'], 'Success Rate', 'of today orders', 'cyan', $todayPanel['success_rate'], '%'],
                [$todayPanel['failed_rate'], 'Failed Rate', 'of today orders', 'orange', $todayPanel['failed_rate'], '%'],
            ] as $ring)
            <div class="ring-item">
                <div class="progress-ring {{ $ring[3] }}" style="--value:{{ $ring[4] }}">{{ number_format($ring[0], $ring[5] ? 1 : 0) }}{{ $ring[5] }}</div>
                <div><strong>{{ $ring[1] }}</strong><span>{{ $ring[2] }}</span></div>
            </div>
            @endforeach
        </div>
    </section>

    <div class="dashboard-grid">
        <section class="dash-card monthly">
            <header class="card-heading">
                <div><span class="eyebrow">PERFORMANCE</span><h2>Monthly Order Stats</h2></div>
                <a href="#">{{ $dashboardYear }}</a>
            </header>
            @php
                $monthlyMax = max(1, $monthlyOrderStats->max('count'));
                $chartPoints = $monthlyOrderStats->values()->map(function ($stat, $index) use ($monthlyMax) {
                    $x = round(($index / 11) * 700, 1);
                    $y = round(220 - (($stat['count'] / $monthlyMax) * 190), 1);
                    return $x.','.$y;
                })->implode(' ');
            @endphp
            <div class="chart">
                <div class="y-labels"><span>{{ $monthlyMax }}</span><span>{{ (int) ceil($monthlyMax / 2) }}</span><span>0</span></div>
                <svg viewBox="0 0 700 230" preserveAspectRatio="none" aria-label="Monthly order statistics chart">
                    <defs><linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#2f6cf3" stop-opacity=".23"/><stop offset="1" stop-color="#2f6cf3" stop-opacity=".08"/></linearGradient></defs>
                    <path class="grid-line" d="M0 1H700M0 115H700M0 229H700"/>
                    <polygon class="area" points="0,230 {{ $chartPoints }} 700,230"/>
                    <polyline class="line" points="{{ $chartPoints }}"/>
                </svg>
                <div class="months">@foreach($monthlyOrderStats as $month)<span title="{{ $month['count'] }} orders">{{ $month['label'] }}</span>@endforeach</div>
            </div>
            <div class="chart-summary">
                <div><span>Total orders</span><strong>{{ number_format($yearOrderSummary['orders']) }} <small>↗</small></strong></div>
                <div><span>Successful</span><strong>{{ number_format($yearOrderSummary['successful']) }} <small>↗</small></strong></div>
                <div><span>Failed</span><strong>{{ number_format($yearOrderSummary['failed']) }}</strong></div>
                <div><span>Total sell</span><strong>৳{{ number_format($yearOrderSummary['sales'], 2) }} <small>↗</small></strong></div>
            </div>
        </section>

        <section class="dash-card todo">
            <header class="card-heading">
                <div><span class="eyebrow">TEAM</span><h2>Agent List</h2></div>
                @if(Auth::user()->user_type === 'admin')
                    <a href="{{ route('agents-index') }}">View all&nbsp; →</a>
                @endif
            </header>
            <div class="todo-list agent-dashboard-list">
                @forelse($dashboardAgents as $agent)
                    @php
                        $agentName = trim($agent->first_name.' '.$agent->last_name)
                            ?: optional($agent->user)->username
                            ?: $agent->agent_id;
                    @endphp
                    <div class="todo-row">
                        <span class="agent-avatar">{{ strtoupper(mb_substr($agentName, 0, 1)) }}</span>
                        <span class="task-name"><strong>{{ $agentName }}</strong><small>{{ $agent->agent_id }}</small></span>
                        <span class="tag info">{{ number_format($agent->assigned_orders_count) }} orders</span>
                    </div>
                @empty
                    <div class="dashboard-empty">No active agents found.</div>
                @endforelse
            </div>
        </section>

        <section class="dash-card sales">
            <header class="card-heading">
                <div><span class="eyebrow">COMMERCE</span><h2>Today Sales Report</h2></div>
            </header>
            <div class="sales-period">
                <div><span class="eyebrow">PERIOD</span><strong>{{ now()->format('d M Y') }}</strong></div>
                <div class="sales-total"><small>৳</small>{{ number_format($todaySalesTotal, 2) }}</div>
            </div>
            <div class="sales-table">
                <div class="sales-row sales-th"><span>ORDER</span><span>STATUS</span><span>TIME</span><span>AMOUNT</span></div>
                @forelse($todaySales as $sale)
                    @php
                        $saleStatus = $sale->delivery_status ?: ($sale->order_status ?: 'Pending');
                        $saleStatusKey = strtolower((string) $saleStatus);
                        $saleTag = in_array($saleStatusKey, ['delivered', 'completed', 'confirmed', 'success', 'successful'])
                            ? 'success'
                            : (in_array($saleStatusKey, ['cancel', 'cancelled', 'failed', 'returned']) ? 'danger' : 'info');
                        $salePositive = $saleTag === 'danger' ? 'negative' : 'positive';
                    @endphp
                    <div class="sales-row">
                        <strong>{{ $sale->custom_order_id ?: '#'.$sale->id }}</strong>
                        <span><i class="tag {{ $saleTag }}">{{ $saleStatus }}</i></span>
                        <span>{{ optional($sale->created_at)->format('h:i A') }}</span>
                        <b class="{{ $salePositive }}">৳{{ number_format((float) $sale->final_price, 2) }}</b>
                    </div>
                @empty
                    <div class="dashboard-empty">No sales found for today.</div>
                @endforelse
            </div>
            <a class="sales-link" href="{{ route('orders-index') }}">Check all orders&nbsp; →</a>
        </section>

        <section class="dash-card weather new-orders-panel">
            <header class="card-heading">
                <div><span class="eyebrow">ORDERS</span><h2>New Order List</h2></div>
                <a href="{{ route('orders-index') }}">View all&nbsp; →</a>
            </header>
            <div class="new-order-dashboard-list">
                @forelse($newOrderList as $newOrder)
                    @php
                        $newOrderCustomer = trim(
                            optional($newOrder->billingAddress)->first_name.' '.
                            optional($newOrder->billingAddress)->last_name
                        ) ?: 'Guest Customer';
                        $assignedAgentName = $newOrder->assignedAgent
                            ? trim($newOrder->assignedAgent->first_name.' '.$newOrder->assignedAgent->last_name)
                            : null;
                    @endphp
                    <article class="new-order-dashboard-row">
                        <div>
                            <a href="{{ Auth::user()->user_type === 'admin' || (string) $newOrder->assigned_agent_id === (string) $currentAgentId ? route('orders-show', $newOrder->id) : '#' }}">
                                {{ $newOrder->custom_order_id ?: '#'.$newOrder->id }}
                            </a>
                            <span>{{ $newOrderCustomer }} · {{ optional($newOrder->created_at)->format('d M, h:i A') }}</span>
                        </div>
                        <strong>৳{{ number_format((float) $newOrder->final_price, 2) }}</strong>
                        <div class="new-order-assignment">
                            @if($assignedAgentName)
                                <span class="assigned-agent-label">
                                    {{ (string) $newOrder->assigned_agent_id === (string) $currentAgentId ? 'Assigned to you' : 'Already assigned by' }}
                                    <b>{{ $assignedAgentName }}</b>
                                </span>
                            @elseif(Auth::user()->user_type === 'agent' && $currentAgentId)
                                <form action="{{ route('orders-claim') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_ids[]" value="{{ $newOrder->id }}">
                                    <input type="hidden" name="return_to" value="dashboard">
                                    <button type="submit">Assign to Me</button>
                                </form>
                            @else
                                <span class="unassigned-agent-label">Unassigned</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="dashboard-empty">No new orders found.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
