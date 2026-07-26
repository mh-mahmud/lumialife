<?php

namespace App\Http\Controllers;

use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductAttributeController extends Controller
{
    public function colors()
    {
        $items = ProductColor::withCount('products')->latest()->paginate(20);

        return view('product_attributes.index', [
            'items' => $items,
            'type' => 'color',
            'title' => 'Product Colors',
        ]);
    }

    public function storeColor(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:product_colors,name'],
            'hex_code' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'status' => ['required', 'boolean'],
        ]);

        ProductColor::create($data);

        return redirect()->route('product-color-list')->with('success', 'Color created successfully.');
    }

    public function editColor(ProductColor $color)
    {
        return view('product_attributes.edit', [
            'item' => $color,
            'type' => 'color',
            'title' => 'Edit Product Color',
        ]);
    }

    public function updateColor(Request $request, ProductColor $color)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('product_colors', 'name')->ignore($color->id)],
            'hex_code' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'status' => ['required', 'boolean'],
        ]);

        $color->update($data);

        return redirect()->route('product-color-list')->with('success', 'Color updated successfully.');
    }

    public function destroyColor(ProductColor $color)
    {
        $color->delete();

        return redirect()->route('product-color-list')->with('success', 'Color deleted successfully.');
    }

    public function sizes()
    {
        $items = ProductSize::withCount('products')->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('product_attributes.index', [
            'items' => $items,
            'type' => 'size',
            'title' => 'Product Sizes',
        ]);
    }

    public function storeSize(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:product_sizes,name'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        ProductSize::create($data);

        return redirect()->route('product-size-list')->with('success', 'Size created successfully.');
    }

    public function editSize(ProductSize $size)
    {
        return view('product_attributes.edit', [
            'item' => $size,
            'type' => 'size',
            'title' => 'Edit Product Size',
        ]);
    }

    public function updateSize(Request $request, ProductSize $size)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('product_sizes', 'name')->ignore($size->id)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $size->update($data);

        return redirect()->route('product-size-list')->with('success', 'Size updated successfully.');
    }

    public function destroySize(ProductSize $size)
    {
        $size->delete();

        return redirect()->route('product-size-list')->with('success', 'Size deleted successfully.');
    }
}
