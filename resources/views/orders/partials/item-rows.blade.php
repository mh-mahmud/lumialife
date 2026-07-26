@forelse($orderDetails as $key => $detail)
    @php $product = $detail->product; @endphp
    <tr data-item-id="{{ $detail->getKey() }}">
        <td data-label="#">{{ $key + 1 }}</td>
        <td data-label="Product"><div class="product-cell"><img src="{{ $product ? \App\Support\MediaStorage::url($product->img_path, 'products') : url('/uploads/noimage.jpg') }}" alt=""><span>{{ $product->name ?? 'Product unavailable' }}</span></div></td>
        <td data-label="Quantity">
            <div class="d-flex align-items-center gap-2">
                <input class="qty item-quantity" type="number" min="1" max="9999" value="{{ $detail->quantity }}" aria-label="Quantity">
                <button type="button" class="btn btn-sm btn-icon btn-light-primary update-item" title="Update quantity">✓</button>
            </div>
        </td>
        <td data-label="Attributes">{{ collect([$detail->product_color ?? null, $detail->product_size ?? null])->filter()->implode(' / ') ?: '—' }}</td>
        <td data-label="Price">৳{{ number_format((float)$detail->unit_price, 2) }}</td>
        <td data-label="Total">৳{{ number_format((float)$detail->quantity * (float)$detail->unit_price, 2) }}</td>
        <td data-label="Action"><button type="button" class="btn btn-sm btn-icon btn-light-danger delete-item" title="Remove product">×</button></td>
    </tr>
@empty
    <tr><td colspan="7" class="text-center text-muted py-10">No items found for this order.</td></tr>
@endforelse
