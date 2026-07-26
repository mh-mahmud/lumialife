<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use Carbon\Carbon;
use App\Services\BrandService;

class BrandController extends Controller
{

    protected $brand_service;

    public function __construct(BrandService $brand_service)
    {
        $this->brand_service = $brand_service;
    }

    public function index()
    {
        
        $brands = $this->brand_service->getAllBrands();
        return view('brands.index', compact('brands'));
    }

    function create() {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|unique:brands,brand_name',
            'brand_image' => 'required|image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
        ]);
       
        // If validation fails,return error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->brand_service->create_brand($request);
        return redirect()->route('brand-list')->with('success', 'Brand created successfully.');
    }


    public function show($id)
    {
        $brand = $this->brand_service->getBrand($id);
        return view('brands.show', $brand);
    }

    public function edit($id)
    {
        $brand = $this->brand_service->getBrandEditData($id);
        return view('brands.edit', $brand);
    }


    public function update(Request $request, $id)
    {
        
        $request->validate([
            'brand_name' => 'required|unique:brands,brand_name,' . $request->id,
            'brand_image' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $this->brand_service->updateBrand($request, $id);
        return redirect()->route('brand-list')->with('success', 'Brand updated successfully.');
    }

  

    public function search(Request $request)
    {

        $searchTerm = trim($request->input('search'));

        if (empty($searchTerm)) {
            return redirect()->route('Brand-list')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);
       
        $brands = $this->brand_service->searchBrand($request);
        return view('brand.index', compact('brands'));
    }




    public function destroy($id)
    {
        try {
            $this->brand_service->deleteBrand($id);
            return redirect()->route('brand-list')->with('success', 'Brand deleted successfully.');
        } catch (\Exception $e) {
           return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateBrandImage($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->brand_image) {
            // path to the image file
            \App\Support\MediaStorage::delete($brand->brand_image, 'brands');
            //Update the Brand record to remove the profile image
            $brand->brand_image = null;
            $brand->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'No Brand image found']);
    }

}
