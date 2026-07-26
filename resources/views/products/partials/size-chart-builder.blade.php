@php
    $chartColumns = old('size_chart_columns', $chartProduct?->size_chart_columns ?? ['Size', 'Chest (round)', 'Length', 'Sleeve']);
    $chartRows = old('size_chart_rows', $chartProduct?->size_chart_rows ?? []);
    if (empty($chartRows)) $chartRows = [['size' => '', 'chest' => '', 'length' => '', 'sleeve' => '']];
@endphp

<div class="col-12 mt-8">
    <div class="card border border-dashed border-gray-300 shadow-none">
        <div class="card-header bg-light">
            <div class="card-title"><div><h3 class="mb-1">Product Size Chart</h3><div class="text-muted fs-7 fw-normal">Enter measurements in inches. CM values will be calculated automatically.</div></div></div>
        </div>
        <div class="card-body size-chart-builder">
            <div class="rounded border border-primary border-dashed bg-light-primary p-5 mb-6">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-7">
                        <label class="form-label fw-bold">Quick Add From Saved Template</label>
                        <select class="form-select form-select-solid size-chart-template-select">
                            <option value="">Select a saved size chart...</option>
                            @foreach($sizeChartTemplates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5"><button type="button" class="btn btn-primary w-100 size-chart-apply-template" disabled><i class="fa fa-bolt"></i> Apply Template</button></div>
                </div>
                @if($sizeChartTemplates->isEmpty())<div class="text-muted fs-7 mt-3">No saved template yet. Complete a chart below and enable “Save as reusable template”.</div>@endif
            </div>
            <div class="row g-4 mb-6">
                <div class="col-lg-6">
                    <label class="form-label fw-bold">Chart Title</label>
                    <input type="text" name="size_chart_title" class="form-control form-control-solid" value="{{ old('size_chart_title', $chartProduct?->size_chart_title ?? 'Size chart - In Inches (Expected Deviation < 3%)') }}">
                </div>
                <div class="col-lg-6 d-flex align-items-end justify-content-lg-end"><button type="button" class="btn btn-sm btn-light-primary size-chart-add-row"><i class="fa fa-plus"></i> Add Size Row</button></div>
            </div>
            <div class="row g-3 mb-4">
                @foreach(['Size', 'Chest (round)', 'Length', 'Sleeve'] as $index => $fallback)
                    <div class="col-6 col-lg-3"><label class="form-label fw-bold">Column {{ $index + 1 }}</label><input type="text" name="size_chart_columns[]" class="form-control form-control-sm form-control-solid" value="{{ $chartColumns[$index] ?? $fallback }}" required></div>
                @endforeach
            </div>
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle mb-0">
                    <thead><tr class="fw-bold text-muted"><th>Size</th><th>Chest (inch)</th><th>Length (inch)</th><th>Sleeve (inch)</th><th class="text-end">Action</th></tr></thead>
                    <tbody class="size-chart-rows">
                    @foreach($chartRows as $index => $row)
                        <tr>
                            <td><input name="size_chart_rows[{{ $index }}][size]" class="form-control form-control-sm" value="{{ $row['size'] ?? '' }}" placeholder="M"></td>
                            <td><input name="size_chart_rows[{{ $index }}][chest]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="{{ $row['chest'] ?? '' }}" placeholder="39"></td>
                            <td><input name="size_chart_rows[{{ $index }}][length]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="{{ $row['length'] ?? '' }}" placeholder="27.5"></td>
                            <td><input name="size_chart_rows[{{ $index }}][sleeve]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="{{ $row['sleeve'] ?? '' }}" placeholder="8.25"></td>
                            <td class="text-end"><button type="button" class="btn btn-sm btn-icon btn-light-danger size-chart-remove-row"><i class="fa fa-times"></i></button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="separator my-6"></div>
            <label class="form-check form-switch form-check-custom form-check-solid mb-4">
                <input type="hidden" name="save_size_chart_template" value="0">
                <input class="form-check-input size-chart-save-template" type="checkbox" name="save_size_chart_template" value="1" @checked(old('save_size_chart_template'))>
                <span class="form-check-label fw-bold">Save this chart as a reusable template</span>
            </label>
            <div class="size-chart-template-name-wrap" @if(!old('save_size_chart_template')) style="display:none" @endif>
                <label class="form-label fw-bold">Template Name</label>
                <input type="text" name="size_chart_template_name" class="form-control form-control-solid" value="{{ old('size_chart_template_name') }}" placeholder="Example: Men's T-Shirt Standard">
                @error('size_chart_template_name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const builder = document.querySelector('.size-chart-builder');
    if (!builder) return;
    const body = builder.querySelector('.size-chart-rows');
    let index = body.querySelectorAll('tr').length;
    const templates = @json($sizeChartTemplates->mapWithKeys(fn ($template) => [$template->id => ['title' => $template->title, 'columns' => $template->columns, 'rows' => $template->rows]]));
    const templateSelect = builder.querySelector('.size-chart-template-select');
    const applyButton = builder.querySelector('.size-chart-apply-template');
    const saveTemplate = builder.querySelector('.size-chart-save-template');
    const templateNameWrap = builder.querySelector('.size-chart-template-name-wrap');

    templateSelect.addEventListener('change', function () { applyButton.disabled = !templateSelect.value; });
    saveTemplate.addEventListener('change', function () { templateNameWrap.style.display = saveTemplate.checked ? '' : 'none'; });

    function rowMarkup(row, rowIndex) {
        const value = key => String(row[key] ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `<tr><td><input name="size_chart_rows[${rowIndex}][size]" class="form-control form-control-sm" value="${value('size')}" placeholder="XL"></td><td><input name="size_chart_rows[${rowIndex}][chest]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="${value('chest')}"></td><td><input name="size_chart_rows[${rowIndex}][length]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="${value('length')}"></td><td><input name="size_chart_rows[${rowIndex}][sleeve]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="${value('sleeve')}"></td><td class="text-end"><button type="button" class="btn btn-sm btn-icon btn-light-danger size-chart-remove-row"><i class="fa fa-times"></i></button></td></tr>`;
    }

    applyButton.addEventListener('click', function () {
        const template = templates[templateSelect.value];
        if (!template) return;
        builder.querySelector('[name="size_chart_title"]').value = template.title || '';
        builder.querySelectorAll('[name="size_chart_columns[]"]').forEach((input, columnIndex) => input.value = template.columns[columnIndex] || '');
        body.innerHTML = '';
        (template.rows || []).forEach(row => { body.insertAdjacentHTML('beforeend', rowMarkup(row, index++)); });
        if (!body.children.length) body.insertAdjacentHTML('beforeend', rowMarkup({}, index++));
        applyButton.innerHTML = '<i class="fa fa-check"></i> Template Applied';
        setTimeout(() => applyButton.innerHTML = '<i class="fa fa-bolt"></i> Apply Template', 1400);
    });
    builder.querySelector('.size-chart-add-row').addEventListener('click', function () {
        const row = document.createElement('tr');
        row.innerHTML = rowMarkup({}, index).replace(/^<tr>|<\/tr>$/g, '');
        body.appendChild(row); index++;
    });
    body.addEventListener('click', function (event) {
        const button = event.target.closest('.size-chart-remove-row');
        if (!button) return;
        if (body.querySelectorAll('tr').length === 1) button.closest('tr').querySelectorAll('input').forEach(input => input.value = '');
        else button.closest('tr').remove();
    });
});
</script>
