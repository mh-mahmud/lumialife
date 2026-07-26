<?php

namespace App\Services;
use App\Models\Product;
use App\Models\SizeChartTemplate;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Support\MediaStorage;
use DB;

class ProductService
{
    public function productList($request)
    {
        /*$sql = Product::with('category')->query();
        $data = $request->all();
        if(!empty($data["search"])) {
            $sql->where('name','like', '%' . $data["search"] . '%');

        }
        if (isset($data['paginate']) && $data['paginate'] == false) {
            return  $sql->orderBy('id', 'DESC')->get();

        } else {
            return  $sql->orderBy('id', 'DESC')->paginate(config('constants.ROW_PER_PAGE'));

        }*/

        $sql = Product::with('category');
        $data = $request->all();

        if (!empty($data["search"])) {
            $sql->where('name', 'like', '%' . $data["search"] . '%');
        }

        if (isset($data['paginate']) && $data['paginate'] == false) {
            return $sql->orderBy('id', 'DESC')->get();
        } else {
            return $sql->orderBy('id', 'DESC')->paginate(config('constants.ROW_PER_PAGE'));
        }

    }

    public function product_stock($request) {

        $sql = Product::with('category');
        $data = $request->all();

        if (!empty($data["search"])) {
            $sql->where('name', 'like', '%' . $data["search"] . '%');
        }

        if (isset($data['paginate']) && $data['paginate'] == false) {
            return $sql->orderBy('id', 'DESC')->get();
        } else {
            return $sql->orderBy('id', 'DESC')->paginate(config('constants.ROW_PER_PAGE'));
        }

    }

    public function productStore($request)
    {

        $request->validate([
            'name' => 'required|unique:products|max:191',
            'product_code' => 'required|max:20',
            'product_type' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'stock_quantity' => 'required|numeric',
            'color_ids' => ['nullable', 'array'],
            'color_ids.*' => ['integer', 'exists:product_colors,id'],
            'size_ids' => ['nullable', 'array'],
            'size_ids.*' => ['integer', 'exists:product_sizes,id'],
            'size_chart_title' => ['nullable', 'string', 'max:191'],
            'size_chart_columns' => ['nullable', 'array', 'size:4'],
            'size_chart_columns.*' => ['nullable', 'string', 'max:100'],
            'size_chart_rows' => ['nullable', 'array'],
            'size_chart_rows.*.size' => ['nullable', 'string', 'max:50'],
            'size_chart_rows.*.chest' => ['nullable', 'numeric', 'min:0'],
            'size_chart_rows.*.length' => ['nullable', 'numeric', 'min:0'],
            'size_chart_rows.*.sleeve' => ['nullable', 'numeric', 'min:0'],
            'save_size_chart_template' => ['nullable', 'boolean'],
            'size_chart_template_name' => ['nullable', 'required_if:save_size_chart_template,1', 'string', 'max:191', 'unique:size_chart_templates,name'],
            'img_path' => 'required|image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_2' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_3' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_4' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_5' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_6' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'product_cost' => [
                'nullable',
                'numeric',
                'regex:/^\d{1,11}(\.\d{1,2})?$/'
            ],
            'product_value' => [
                'required',
                'numeric',
                'regex:/^\d{1,11}(\.\d{1,2})?$/'
            ],
        ], [
            'product_cost.regex' => 'The product cost must have at most 11 digits before the decimal point and up to 2 digits after the decimal point.',
            'product_value.regex' => 'The product value must have at most 11 digits before the decimal point and up to 2 digits after the decimal point.',
        ]);
        $data = $request->all();


        // upload main image
        $fileNameToStore = '';
        if ($request->hasFile('img_path')) {
            $fileNameToStore = MediaStorage::store($request->file('img_path'), 'products');
        }

        $fileNameToStore_2 = '';
        if ($request->hasFile('img_path_2')) {
            $fileNameToStore_2 = MediaStorage::store($request->file('img_path_2'), 'products');
        }

        $fileNameToStore_3 = '';
        if ($request->hasFile('img_path_3')) {
            $fileNameToStore_3 = MediaStorage::store($request->file('img_path_3'), 'products');
        }

        $fileNameToStore_4 = $request->hasFile('img_path_4') ? MediaStorage::store($request->file('img_path_4'), 'products') : '';
        $fileNameToStore_5 = $request->hasFile('img_path_5') ? MediaStorage::store($request->file('img_path_5'), 'products') : '';
        $fileNameToStore_6 = $request->hasFile('img_path_6') ? MediaStorage::store($request->file('img_path_6'), 'products') : '';

        try {
            return DB::transaction(function () use ($data, $fileNameToStore, $fileNameToStore_2, $fileNameToStore_3, $fileNameToStore_4, $fileNameToStore_5, $fileNameToStore_6, $request) {
                $dataObj                        = new Product();
                $dataObj->name                  = $data['name'];
                $dataObj->product_code          = $data['product_code'];
                $dataObj->category_id           = $data['category_id'];
                $dataObj->brand_id              = $data['brand_id'];
                $dataObj->product_type          = $data['product_type'];
                $dataObj->product_cost          = $data['product_cost'];
                $dataObj->product_value         = $data['product_value'];
                $dataObj->discount_price        = $data['discount_price'];
                $dataObj->description           = $data['description'];
                //$dataObj->key_features          = $data['key_features'];
                $dataObj->key_features          = "";
                //$dataObj->club_point            = $data['club_point'];
                $dataObj->club_point            = 0;
                $dataObj->product_specification = $data['product_specification'];
                $dataObj->size_chart_title      = $request->input('size_chart_title');
                $dataObj->size_chart_columns    = $this->sizeChartColumns($request);
                $dataObj->size_chart_rows       = $this->sizeChartRows($request);
                $dataObj->img_path              = $fileNameToStore;
                $dataObj->img_path_2            = $fileNameToStore_2;
                $dataObj->img_path_3            = $fileNameToStore_3;
                $dataObj->img_path_4            = $fileNameToStore_4;
                $dataObj->img_path_5            = $fileNameToStore_5;
                $dataObj->img_path_6            = $fileNameToStore_6;
                $dataObj->stock_status          = $data['stock_status'];
                $dataObj->stock_quantity        = $data['stock_quantity'];
                //$dataObj->max_purchase_limit    = $data['max_purchase_limit'];
                $dataObj->max_purchase_limit    = 10;
                $dataObj->status                = $data['status'];
                $dataObj->is_trending           = $request->boolean('is_trending');
                $dataObj->is_lifestyle          = $request->boolean('is_lifestyle');
                $dataObj->is_best_deal          = $request->boolean('is_best_deal');
                $dataObj->created_by            = Auth::id();
                $dataObj->save();
                $dataObj->productColors()->sync($request->input('color_ids', []));
                $dataObj->productSizes()->sync($request->input('size_ids', []));
                $this->saveSizeChartTemplate($request, $dataObj);

                Helper::storeLog($data['name'], "Products", "Add Product {$data['name']}", "Added");

                return (object)[
                    'status'                 => 201,
                    'info'                   => $dataObj->id
                ];
            });
        } catch (Exception $e) {
            dd($e->getMessage());
            return (object)[
                'status'             => 424,
                'error'              => $e->getMessage()
            ];
        }
    }

    public function productUpdate($request, $id)
    {

        $request->validate([
            'name' => 'required|unique:products,name,' . $request->id,
            'product_code' => 'required|max:20',
            'product_type' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'description' => 'required',
            'stock_quantity' => 'required|numeric',
            'color_ids' => ['nullable', 'array'],
            'color_ids.*' => ['integer', 'exists:product_colors,id'],
            'size_ids' => ['nullable', 'array'],
            'size_ids.*' => ['integer', 'exists:product_sizes,id'],
            'size_chart_title' => ['nullable', 'string', 'max:191'],
            'size_chart_columns' => ['nullable', 'array', 'size:4'],
            'size_chart_columns.*' => ['nullable', 'string', 'max:100'],
            'size_chart_rows' => ['nullable', 'array'],
            'size_chart_rows.*.size' => ['nullable', 'string', 'max:50'],
            'size_chart_rows.*.chest' => ['nullable', 'numeric', 'min:0'],
            'size_chart_rows.*.length' => ['nullable', 'numeric', 'min:0'],
            'size_chart_rows.*.sleeve' => ['nullable', 'numeric', 'min:0'],
            'save_size_chart_template' => ['nullable', 'boolean'],
            'size_chart_template_name' => ['nullable', 'required_if:save_size_chart_template,1', 'string', 'max:191', 'unique:size_chart_templates,name'],
            'img_path' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_2' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_3' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_4' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_5' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'img_path_6' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:1048',
            'product_cost' => [
                'nullable',
                'numeric',
                'regex:/^\d{1,11}(\.\d{1,2})?$/'
            ],
            'product_value' => [
                'required',
                'numeric',
                'regex:/^\d{1,11}(\.\d{1,2})?$/'
            ],
        ], [
            'product_cost.regex' => 'The product cost must have at most 11 digits before the decimal point and up to 2 digits after the decimal point.',
            'product_value.regex' => 'The product value must have at most 11 digits before the decimal point and up to 2 digits after the decimal point.',
        ]);


        $data = $request->all();
        $fileNameToStore = '';
        if ($request->hasFile('img_path')) {
            $fileNameToStore = MediaStorage::store($request->file('img_path'), 'products');
            
        }

        $fileNameToStore_2 = '';
        if ($request->hasFile('img_path_2')) {
            $fileNameToStore_2 = MediaStorage::store($request->file('img_path_2'), 'products');
        }

        $fileNameToStore_3 = '';
        if ($request->hasFile('img_path_3')) {
            $fileNameToStore_3 = MediaStorage::store($request->file('img_path_3'), 'products');
        }

        $fileNameToStore_4 = $request->hasFile('img_path_4') ? MediaStorage::store($request->file('img_path_4'), 'products') : '';
        $fileNameToStore_5 = $request->hasFile('img_path_5') ? MediaStorage::store($request->file('img_path_5'), 'products') : '';
        $fileNameToStore_6 = $request->hasFile('img_path_6') ? MediaStorage::store($request->file('img_path_6'), 'products') : '';

        try {
            return  DB::transaction(function () use ($data, $fileNameToStore, $fileNameToStore_2, $fileNameToStore_3, $fileNameToStore_4, $fileNameToStore_5, $fileNameToStore_6, $request, $id) {
                $dataObj                        = Product::findOrFail($id);;
                $dataObj->name                  = $data['name'];
                $dataObj->product_code          = $data['product_code'];
                $dataObj->category_id           = $data['category_id'];
                $dataObj->brand_id              = $data['brand_id'];
                $dataObj->product_type          = $data['product_type'];
                $dataObj->product_cost          = $data['product_cost'];
                $dataObj->product_value         = $data['product_value'];
                $dataObj->discount_price         = $data['discount_price'];
                $dataObj->description           = $data['description'];
                //$dataObj->key_features          = $data['key_features'];
                //$dataObj->club_point            = $data['club_point'];
                $dataObj->product_specification = $data['product_specification'];
                $dataObj->size_chart_title      = $request->input('size_chart_title');
                $dataObj->size_chart_columns    = $this->sizeChartColumns($request);
                $dataObj->size_chart_rows       = $this->sizeChartRows($request);
                $oldImages = [$dataObj->img_path, $dataObj->img_path_2, $dataObj->img_path_3, $dataObj->img_path_4, $dataObj->img_path_5, $dataObj->img_path_6];
                $dataObj->img_path              = $request->hasFile('img_path') ? $fileNameToStore : $dataObj->img_path;
                $dataObj->img_path_2            = $request->hasFile('img_path_2') ? $fileNameToStore_2 : $dataObj->img_path_2;
                $dataObj->img_path_3            = $request->hasFile('img_path_3') ? $fileNameToStore_3 : $dataObj->img_path_3;
                $dataObj->img_path_4            = $request->hasFile('img_path_4') ? $fileNameToStore_4 : $dataObj->img_path_4;
                $dataObj->img_path_5            = $request->hasFile('img_path_5') ? $fileNameToStore_5 : $dataObj->img_path_5;
                $dataObj->img_path_6            = $request->hasFile('img_path_6') ? $fileNameToStore_6 : $dataObj->img_path_6;
                $dataObj->stock_status          = $data['stock_status'];
                $dataObj->stock_quantity        = $data['stock_quantity'];
                //$dataObj->max_purchase_limit    = $data['max_purchase_limit'];
                $dataObj->status                = $data['status'];
                $dataObj->is_trending           = $request->boolean('is_trending');
                $dataObj->is_lifestyle          = $request->boolean('is_lifestyle');
                $dataObj->is_best_deal          = $request->boolean('is_best_deal');
                $dataObj->updated_by            = Auth::id();
                $dataObj->save();
                foreach (['img_path', 'img_path_2', 'img_path_3', 'img_path_4', 'img_path_5', 'img_path_6'] as $index => $field) {
                    if ($request->hasFile($field)) {
                        MediaStorage::delete($oldImages[$index], 'products');
                    }
                }
                $dataObj->productColors()->sync($request->input('color_ids', []));
                $dataObj->productSizes()->sync($request->input('size_ids', []));
                $this->saveSizeChartTemplate($request, $dataObj);
                Helper::storeLog($data['name'], "Products", "Update Product", "Updated");

                return (object)[
                    'status'                 => 208,
                    'info'                   => $dataObj->id
                ];
            });


        } catch (Exception $e) {
            dd($e->getMessage());
            return (object)[
                'status'             => 424,
                'error'              => $e->getMessage()
            ];
        }

    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    private function sizeChartColumns($request): array
    {
        $defaults = ['Size', 'Chest (round)', 'Length', 'Sleeve'];

        return collect($request->input('size_chart_columns', $defaults))
            ->map(fn ($column, $index) => trim((string) $column) ?: $defaults[$index])
            ->values()
            ->all();
    }

    private function sizeChartRows($request): array
    {
        return collect($request->input('size_chart_rows', []))
            ->map(fn ($row) => [
                'size' => trim((string) ($row['size'] ?? '')),
                'chest' => $row['chest'] ?? null,
                'length' => $row['length'] ?? null,
                'sleeve' => $row['sleeve'] ?? null,
            ])
            ->filter(fn ($row) => $row['size'] !== '' && $row['chest'] !== null && $row['length'] !== null && $row['sleeve'] !== null)
            ->values()
            ->all();
    }

    private function saveSizeChartTemplate($request, Product $product): void
    {
        if (!$request->boolean('save_size_chart_template') || empty($product->size_chart_rows)) {
            return;
        }

        SizeChartTemplate::create([
            'name' => trim((string) $request->input('size_chart_template_name')),
            'title' => $product->size_chart_title,
            'columns' => $product->size_chart_columns,
            'rows' => $product->size_chart_rows,
            'is_active' => true,
        ]);
    }

    public function productDelete($id)
    {
        try {
            return  DB::transaction(function () use ($id) {
                $data = Product::findOrFail($id);
                foreach ([$data->img_path, $data->img_path_2, $data->img_path_3, $data->img_path_4, $data->img_path_5, $data->img_path_6] as $image) {
                    MediaStorage::delete($image, 'products');
                }
                $data->delete();

                Helper::storeLog($data->name, "Products", "Delete Product", "Deleted");

                return (object)[
                    'status'                 => 200,
                ];

            });
        } catch (Exception $e) {
            return (object)[
                'status'             => 424,
                'error'              => $e->getMessage()
            ];
        }
    }
}
