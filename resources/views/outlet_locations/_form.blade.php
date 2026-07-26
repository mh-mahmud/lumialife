<div class="row g-5">
    <div class="col-md-6 fv-row">
        <label class="form-label fw-bolder">Location Name <span class="text-danger">*</span></label>
        <input type="text" name="location_name" value="{{ old('location_name', $outletLocation->location_name ?? '') }}"
            class="form-control form-control-solid" placeholder="e.g. Dhanmondi (Dhaka)" required>
        @error('location_name')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 fv-row">
        <label class="form-label fw-bolder">Hotline <span class="text-danger">*</span></label>
        <input type="text" name="hotline" value="{{ old('hotline', $outletLocation->hotline ?? '') }}"
            class="form-control form-control-solid" placeholder="e.g. 01300000000" required>
        @error('hotline')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-12 fv-row">
        <label class="form-label fw-bolder">Address <span class="text-danger">*</span></label>
        <textarea name="address" rows="3" class="form-control form-control-solid"
            placeholder="Full outlet address" required>{{ old('address', $outletLocation->address ?? '') }}</textarea>
        @error('address')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-12 fv-row">
        <label class="form-label fw-bolder">Google Map <span class="text-danger">*</span></label>
        <textarea name="map_url" rows="4" class="form-control form-control-solid"
            placeholder="Paste Google Maps embed URL or the complete iframe code" required>{{ old('map_url', $outletLocation->map_url ?? '') }}</textarea>
        <div class="form-text">Google Maps → Share → Embed a map থেকে URL অথবা পুরো iframe code paste করুন।</div>
        @error('map_url')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 fv-row">
        <label class="form-label fw-bolder">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $outletLocation->sort_order ?? 0) }}"
            min="0" class="form-control form-control-solid">
        @error('sort_order')<span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="col-md-6 fv-row">
        <label class="form-label fw-bolder">Status</label>
        @php($status = old('status', isset($outletLocation) ? (string) (int) $outletLocation->status : '1'))
        <select name="status" class="form-select form-select-solid" required>
            <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
    </div>
</div>
