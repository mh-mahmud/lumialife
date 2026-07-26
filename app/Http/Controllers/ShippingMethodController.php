<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::latest()->paginate(20);

        return view('shipping_methods.index', compact('shippingMethods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:shipping_methods,name'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'status' => ['required', 'boolean'],
        ]);

        ShippingMethod::create($data);

        return redirect()->route('shipping-method-list')->with('success', 'Shipping method created successfully.');
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('shipping_methods.edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('shipping_methods', 'name')->ignore($shippingMethod->id)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'status' => ['required', 'boolean'],
        ]);

        $shippingMethod->update($data);

        return redirect()->route('shipping-method-list')->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return redirect()->route('shipping-method-list')->with('success', 'Shipping method deleted successfully.');
    }
}
