@extends('layouts.master')

@php
use Carbon\Carbon;
@endphp

@section('content')

<style>
    .orders-page {
        color: #2f3b5f;
    }

    .orders-toolbar {
        gap: 8px;
    }

    .orders-toolbar .form-select,
    .orders-toolbar .form-control {
        height: 30px;
        font-size: 11px;
        border-radius: 6px;
    }

    .orders-table {
        font-size: 11px;
        color: #26345d;
    }

    .orders-table thead th,
    .orders-table tfoot th {
        background: #fff;
        color: #27365f;
        font-size: 11px;
        font-weight: 700;
        padding: 10px 8px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .orders-table tbody td {
        padding: 14px 8px;
        vertical-align: middle;
        border-color: #edf0f5;
    }

    .orders-table tbody tr:nth-child(even) {
        background: #fbfcfe;
    }

    .order-id-badge,
    .order-source-badge,
    .order-payment-badge {
        border-radius: 3px;
        color: #fff;
        display: inline-flex;
        font-size: 9px;
        font-weight: 700;
        line-height: 1;
        padding: 4px 6px;
        white-space: nowrap;
    }

    .order-id-badge {
        background: #4f8bdc;
    }

    .order-source-badge {
        background: #3d7fd6;
    }

    .order-payment-badge.cod {
        background: #ed4961;
    }

    .order-payment-badge.paid {
        background: #26b983;
    }

    .order-customer {
        max-width: 180px;
        line-height: 1.25;
    }

    .order-customer strong {
        color: #26345d;
        display: block;
        font-size: 11px;
    }

    .order-customer .order-address {
        color: #34405f;
        margin-top: 2px;
    }

    .order-customer .order-date {
        color: #516082;
        margin-top: 8px;
    }

    .order-products {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 78px;
    }

    .order-product-thumb {
        background: #fff;
        border: 3px solid #b9b4cf;
        box-shadow: 0 1px 2px rgba(24, 34, 70, .08);
        padding: 2px;
        text-align: center;
    }

    .order-product-thumb img {
        height: 76px;
        object-fit: cover;
        width: 100%;
    }

    .order-product-thumb span {
        color: #1f2c52;
        display: block;
        font-size: 10px;
        font-weight: 700;
        margin-top: 2px;
    }

    .order-action-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        min-width: 260px;
    }

    .order-action-links a,
    .order-action-links button,
    .order-action-links span {
        background: transparent;
        border: 0;
        color: #7258d8;
        font-size: 10px;
        font-weight: 600;
        padding: 0;
        white-space: nowrap;
    }

    .order-action-links button:hover,
    .order-action-links a:hover {
        color: #3f2eb3;
    }

    .orders-bottom-tools {
        background: #eef1f7;
        border-top: 1px solid #dce2ee;
        gap: 8px;
        padding: 8px;
    }
    .fraud-skeleton{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.fraud-skeleton i{display:block;height:105px;border-radius:8px;background:linear-gradient(90deg,#eef1f5 25%,#f8f9fb 50%,#eef1f5 75%);background-size:200% 100%;animation:fraudShimmer 1.2s infinite}.fraud-summary{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:18px}.fraud-stat,.courier-result{border:1px solid #e2e7ef;border-radius:8px;padding:13px;background:#fff}.fraud-stat small{display:block;color:#8190aa;margin-bottom:5px}.fraud-stat b{font-size:18px}.courier-results{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}.courier-result{display:flex;align-items:center;gap:11px}.courier-result img{width:42px;height:42px;object-fit:contain}.courier-result strong,.courier-result small{display:block}.courier-result small{color:#71819d;margin-top:3px}.fraud-report{border-left:3px solid #ef5570;background:#fff5f7;padding:10px 12px;margin-top:9px}.fraud-error{padding:18px;text-align:center;color:#c3344d;background:#fff3f5;border-radius:8px}@keyframes fraudShimmer{to{background-position:-200% 0}}@media(max-width:700px){.fraud-summary{grid-template-columns:1fr 1fr}.courier-results,.fraud-skeleton{grid-template-columns:1fr}}
</style>

<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Orders
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <small class="text-muted fs-7 fw-bold my-1 ms-1">List</small>
            </h1>
        </div>
        <div class="d-flex align-items-center py-1">
            <a href="{{ route('dashboard') }}" class="text-muted fw-bold fs-7 me-2">Admin</a>
            <span class="text-muted fw-bold fs-7 me-2">/ Orders /</span>
            <span class="text-primary fw-bold fs-7">List</span>
        </div>
    </div>
</div>

<div class="container-fluid orders-page">
    <form id="assignAgentForm" action="{{ route('orders-assign-agent') }}" method="POST">@csrf</form>
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success')}}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error')}}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header border-0 bg-transparent p-4 pb-2">
            <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-3">
                <div>
                    <h3 class="fw-bolder mb-1">Orders</h3>
                    <div class="text-muted fw-bold fs-8">
                        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                        @if(request('search'))
                            <span>(filtered)</span>
                            <a href="{{ route('orders-index') }}" class="ms-1">Reset</a>
                        @endif
                    </div>
                </div>

                <form action="{{ route('orders-search') }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" name="search" class="form-control form-control-sm form-control-solid w-250px"
                        value="{{ request('search') }}" placeholder="Search...">
                </form>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between w-100 mt-3 gap-3">
                <div class="d-flex flex-wrap align-items-center orders-toolbar">
                    <select name="agent_id" form="assignAgentForm" class="form-select form-select-sm form-select-solid w-200px" required>
                        <option value="">Select Agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->agent_id }}">{{ trim($agent->first_name.' '.$agent->last_name) }} ({{ $agent->agent_id }})</option>
                        @endforeach
                    </select>
                    <button type="submit" form="assignAgentForm" class="btn btn-sm btn-success">Assign Agent</button>
                    <select id="courierProvider" class="form-select form-select-sm form-select-solid w-160px">
                        <option value="steadfast">SteadFast</option>
                        <option value="redx" disabled>RedX — Coming soon</option>
                        <option value="pathao" disabled>Pathao — Coming soon</option>
                    </select>
                    <button type="button" id="bulkCourierSend" class="btn btn-sm btn-warning">Send to Courier</button>
                    <a href="{{ route('orders-create') }}" class="btn btn-sm btn-primary">Create Order</a>
                </div>

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                @if($orders->isNotEmpty())
                <table class="table orders-table table-row-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 w-30px"><input type="checkbox" class="form-check-input order-select-all"></th>
                            <th>Invoice no</th>
                            <th>Assigned by staff</th>
                            <th>Source</th>
                            <th>Customer</th>
                            <th>Product image</th>
                            <th>Total amount</th>
                            <th>Due Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        @php
                            $details = $order->orderDetails;
                            $isPaid = strtoupper((string) $order->payment_status) === 'PAID';
                            $source = $order->user_id ? 'New Customer' : 'previous customer';
                            $customerName = trim($order->first_name . ' ' . $order->last_name);
                            $address = collect([$order->shipping_address, $order->city, $order->state, $order->zip])->filter()->implode(', ');
                            $canDelete = strcasecmp(trim((string) $order->order_status), 'Pending') === 0;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted me-2">::</span>
                                <input type="checkbox" name="order_ids[]" value="{{ $order->lukaku }}" form="assignAgentForm" class="form-check-input order-checkbox">
                            </td>
                            <td><span class="order-id-badge">{{ $order->custom_order_id }}</span></td>
                            <td>{{ $order->assigned_staff ?: '-' }}</td>
                            <td><span class="order-source-badge">{{ $source }}</span></td>
                            <td>
                                <div class="order-customer">
                                    <strong>{{ $customerName ?: 'Guest Customer' }}</strong>
                                    <div>{{ $order->order_phone_number }}</div>
                                    @if($address)
                                    <div class="order-address">{{ $address }}</div>
                                    @endif
                                    <div class="order-date">{{ Carbon::parse($order->created_at)->format('Y-m-d H:i:s') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="order-products">
                                    @forelse($details as $detail)
                                    <div class="order-product-thumb">
                                        @if($detail->product && $detail->product->img_path)
                                        <img src="{{ \App\Support\MediaStorage::url($detail->product->img_path, 'products') }}" alt="{{ $detail->product->name }}">
                                        @else
                                        <img src="{{ asset('uploads/noimage.jpg') }}" alt="No image">
                                        @endif
                                        <span>Quantity: {{ $detail->quantity }}</span>
                                    </div>
                                    @empty
                                    <div class="order-product-thumb">
                                        <img src="{{ asset('uploads/noimage.jpg') }}" alt="No image">
                                        <span>Quantity: 0</span>
                                    </div>
                                    @endforelse
                                </div>
                            </td>
                            <td class="fw-bold">৳{{ number_format((float) $order->final_price) }}</td>
                            <td>
                                <span class="order-payment-badge {{ $isPaid ? 'paid' : 'cod' }}">
                                    {{ $isPaid ? 'PAID' : 'COD' }}
                                </span>
                            </td>
                            <td>
                                <div class="order-action-links">
                                    <a href="{{ route('orders-show', $order->lukaku) }}">Preview</a>
                                    @if($canDelete)
                                        <form action="{{ route('orders-destroy', $order->lukaku) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirmDelete()">Delete</button>
                                        </form>
                                    @else
                                        <button type="button" disabled title="Only orders with Pending order status can be deleted" style="opacity:.45;cursor:not-allowed">Delete</button>
                                    @endif
                                    <button type="button" class="fraud-check-btn" data-order-id="{{ $order->lukaku }}" data-phone="{{ $order->order_phone_number }}">Fraud Check</button>
                                    <button type="button" class="single-courier-send" data-order-id="{{ $order->lukaku }}" @disabled($order->steadfast_consignment_id)>{{ $order->steadfast_consignment_id ? 'Courier Sent' : 'Send Courier' }}</button>
                                    <span>Courier Status</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="ps-4"><input type="checkbox" class="form-check-input order-select-all"></th>
                            <th>Invoice no</th>
                            <th>Assigned by staff</th>
                            <th>Source</th>
                            <th>Customer</th>
                            <th>Product image</th>
                            <th>Total amount</th>
                            <th>Due Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="p-5 text-muted">No results found.</div>
                @endif
            </div>

            <div class="d-flex flex-wrap align-items-center orders-bottom-tools">
                <button type="button" class="btn btn-sm btn-light">Print invoice</button>
                <button type="button" class="btn btn-sm btn-light">Print sticker</button>
                <button type="button" class="btn btn-sm btn-light">Download product image</button>
                <button type="button" class="btn btn-sm btn-warning">Publish For Shipping</button>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-3">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm form-select-solid w-70px">
                <option>{{ $orders->perPage() }}</option>
            </select>
            <span class="text-muted fs-7">entries per page</span>
        </div>

        @include('components.pagination', ['paginator' => $orders])
    </div>
</div>

<div class="modal fade" id="fraudCheckModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content">
        <div class="modal-header"><div><h5 class="modal-title">Customer Fraud Check</h5><div class="text-muted fs-7" id="fraudCheckPhone"></div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div id="fraudSkeleton" class="fraud-skeleton"><i></i><i></i><i></i><i></i><i></i><i></i></div>
            <div id="fraudResult" class="d-none"></div>
        </div>
    </div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete Orders?");
    }

    document.querySelectorAll('.order-select-all').forEach(function (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.order-checkbox').forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
            document.querySelectorAll('.order-select-all').forEach(function (other) {
                other.checked = selectAll.checked;
            });
        });
    });

    document.getElementById('assignAgentForm').addEventListener('submit', function (event) {
        if (!document.querySelector('.order-checkbox:checked')) {
            event.preventDefault();
            alert('Please select at least one order.');
        }
    });

    const fraudModalElement = document.getElementById('fraudCheckModal');
    let fraudModal = null;
    const fraudSkeleton = document.getElementById('fraudSkeleton');
    const fraudResult = document.getElementById('fraudResult');
    const fraudCheckBase = @json(url('/orders'));
    const bulkCourierUrl = @json(route('orders-courier-bulk-send'));
    axios.defaults.headers.common['X-CSRF-TOKEN'] = @json(csrf_token());
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    const safe = value => String(value ?? '').replace(/[&<>'"]/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[character]));
    const number = value => Number(value || 0).toLocaleString('en-US');

    function renderFraudResult(payload) {
        const data = payload.data || {};
        const summary = data.summary || {};
        const couriers = Object.entries(data).filter(([key, value]) => key !== 'summary' && value && typeof value === 'object');
        const reports = Array.isArray(payload.reports) ? payload.reports : [];
        const courierHtml = couriers.map(([, courier]) => {
            const logo = /^https:\/\//i.test(courier.logo || '') ? `<img src="${safe(courier.logo)}" alt="">` : '';
            return `<div class="courier-result">${logo}<div><strong>${safe(courier.name)}</strong><small>Total ${number(courier.total_parcel)} · Success ${number(courier.success_parcel)} · Cancelled ${number(courier.cancelled_parcel)}</small><small>Success ratio: <b>${number(courier.success_ratio)}%</b></small></div></div>`;
        }).join('');
        const reportHtml = reports.length ? `<h6 class="mt-6 mb-3">Fraud Reports (${reports.length})</h6>` + reports.map(report => `<div class="fraud-report"><strong>${safe(report.name || 'Reported customer')}</strong><div>${safe(report.details || '')}</div><small>${safe(report.courierName || '')} ${safe(report.created_at || '')}</small></div>`).join('') : '<div class="alert alert-success mt-5 mb-0">No fraud reports found.</div>';
        fraudResult.innerHTML = `<div class="fraud-summary"><div class="fraud-stat"><small>Total Parcels</small><b>${number(summary.total_parcel)}</b></div><div class="fraud-stat"><small>Successful</small><b class="text-success">${number(summary.success_parcel)}</b></div><div class="fraud-stat"><small>Cancelled</small><b class="text-danger">${number(summary.cancelled_parcel)}</b></div><div class="fraud-stat"><small>Success Ratio</small><b>${number(summary.success_ratio)}%</b></div></div><div class="courier-results">${courierHtml}</div>${reportHtml}`;
    }

    document.addEventListener('click', async function (event) {
        const button = event.target.closest('.fraud-check-btn');
        if (!button) return;
        if (!window.bootstrap || !window.bootstrap.Modal) {
            alert('Modal component is still loading. Please try again.');
            return;
        }
        fraudModal = fraudModal || window.bootstrap.Modal.getOrCreateInstance(fraudModalElement);
        document.getElementById('fraudCheckPhone').textContent = 'Phone: ' + (button.dataset.phone || 'N/A');
        fraudResult.classList.add('d-none'); fraudSkeleton.classList.remove('d-none'); fraudResult.innerHTML = '';
        fraudModal.show();
        try {
            const response = await axios.post(`${fraudCheckBase}/${button.dataset.orderId}/fraud-check`);
            if (response.data.status && response.data.status !== 'success') throw new Error(response.data.message || 'Fraud check failed.');
            renderFraudResult(response.data);
        } catch (error) {
            fraudResult.innerHTML = `<div class="fraud-error">${safe(error.response?.data?.message || error.message || 'Unable to complete fraud check.')}</div>`;
        } finally {
            fraudSkeleton.classList.add('d-none'); fraudResult.classList.remove('d-none');
        }
    });

    function courierFeedback(message, icon = 'success') {
        if (window.Swal) Swal.fire({icon, title: icon === 'success' ? 'Success' : 'Error', text: message});
        else alert(message);
    }

    function markCourierSent(orderIds) {
        orderIds.forEach(id => {
            const button = document.querySelector(`.single-courier-send[data-order-id="${id}"]`);
            if (button) { button.disabled = true; button.textContent = 'Courier Sent'; }
        });
    }

    document.addEventListener('click', async function (event) {
        const button = event.target.closest('.single-courier-send');
        if (!button || button.disabled) return;
        if (!confirm('Send this order to SteadFast courier?')) return;
        const original = button.textContent; button.disabled = true; button.textContent = 'Sending…';
        try {
            const response = await axios.post(`${fraudCheckBase}/${button.dataset.orderId}/steadfast/place`);
            button.textContent = 'Courier Sent'; courierFeedback(response.data.message);
        } catch (error) {
            button.disabled = false; button.textContent = original; courierFeedback(error.response?.data?.message || 'Unable to send order.', 'error');
        }
    });

    document.getElementById('bulkCourierSend').addEventListener('click', async function () {
        const orderIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(checkbox => Number(checkbox.value));
        if (!orderIds.length) { courierFeedback('Please select at least one order.', 'error'); return; }
        const provider = document.getElementById('courierProvider').value;
        if (!confirm(`Send ${orderIds.length} selected order(s) to SteadFast?`)) return;
        const original = this.textContent; this.disabled = true; this.textContent = 'Sending…';
        try {
            const response = await axios.post(bulkCourierUrl, {provider, order_ids: orderIds});
            markCourierSent(response.data.submitted_order_ids || []); courierFeedback(response.data.message);
        } catch (error) {
            courierFeedback(error.response?.data?.message || 'Unable to send selected orders.', 'error');
        } finally { this.disabled = false; this.textContent = original; }
    });
</script>

@endsection
