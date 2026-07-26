<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\SizeChartTemplate;

class ProductController extends Controller {

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->middleware('auth');
    }

    public function productList(Request $request)
    {
        $products = $this->productService->productList($request);
        return view('products.product-list', compact('products'));
    }

    public function product_stock_report(Request $request)
    {
        $products = $this->productService->product_stock($request);
        return view('products.product-list', compact('products'));
    }

    public function productCreate()
    {
        $categories = Category::where('status', 1)->get(['id', 'category_name']);
        $brands = Brand::where('status', 1)->get(['id', 'brand_name']);
        $colors = ProductColor::where('status', 1)->orderBy('name')->get(['id', 'name', 'hex_code']);
        $sizes = ProductSize::where('status', 1)->orderBy('sort_order')->orderBy('name')->get(['id', 'name']);
        $sizeChartTemplates = SizeChartTemplate::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories', 'brands', 'colors', 'sizes', 'sizeChartTemplates'));
    }

    public function productStore(Request $request)
    {
        $result = $this->productService->productStore($request);
        if($result->status == 201){
            return redirect()->route('product-list')->with('success', 'Product added successfully.');

        }else{
            session()->flash('error', 'Can not Add!');
        }

    }

    public function productShow($id)
    {
        $product = $this->productService->getProductById($id);
        return view('products.product-show', compact('product'));
    }

    public function productEdit($id)
    {
        $categories = Category::where('status', 1)->get(['id', 'category_name']);
        $brands = Brand::where('status', 1)->get(['id', 'brand_name']);
        $colors = ProductColor::orderByDesc('status')->orderBy('name')->get(['id', 'name', 'hex_code', 'status']);
        $sizes = ProductSize::orderByDesc('status')->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'status']);
        $product = $this->productService->getProductById($id)->load(['productColors', 'productSizes']);
        $sizeChartTemplates = SizeChartTemplate::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'brands', 'colors', 'sizes', 'sizeChartTemplates'));
    }

    public function productUpdate(Request $request, $id)
    {
        //dd($request->all());
        $result = $this->productService->productUpdate($request, $id);
        if($result->status == 208){
            return redirect()->route('product-list')->with('success', 'Product updated successfully.');

        }else{
            session()->flash('error', 'Can not Update!');
        }

    }


    public function productDelete($id)
    {
        $result = $this->productService->productDelete($id);
        if($result->status == 200){
            return redirect()->route('product-list')->with('success', 'Product deleted successfully.');

        }else{
            session()->flash('error', 'Can not Delete !');
        }
    }

}
