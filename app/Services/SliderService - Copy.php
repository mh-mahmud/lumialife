<?php

namespace App\Services;

use App\Models\Brand;
use App\Support\MediaStorage;

class BrandService
{


    public function getAllBrands()
    {
        return Brand::orderBy('created_at', 'desc')->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function create_brand($request)
    {
        if ($request->hasFile('brand_image')) {
            $fileNameToStore = MediaStorage::store($request->file('brand_image'), 'brands');
            
        } else {
            
            $fileNameToStore = '';
        }
        
        $brand = new Brand([
            'brand_name' => $request->brand_name,
            'brand_image' => $fileNameToStore,
            'brand_description' => $request->brand_description,
            'status' => $request->status
        ]);

        $brand->save();
        // $user->agent()->save($agent);
        return $brand;
    }

    public function getBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return compact('brand');
    }

    public function getBrandEditData($id)
    {
        $brand = Brand::findOrFail($id);
        return compact('brand');
    }


    public function updateBrand($request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->brand_name = $request->brand_name;
        $brand->brand_description = $request->brand_description;
        $brand->status = $request->status;

        if ($request->hasFile('brand_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('brand_image'), 'brands', $brand->brand_image);
            $brand->brand_image = $fileNameToStore;
               
        }
        $brand->save();
        return $brand;
    }

    public function searchBrand($request)
    {
        $searchTerm = trim($request->input('search'));
        $query = Brand::query();

        $query->where(function($q) use ($searchTerm) {
            $q->where('brand_name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('brand_image', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('brand_description', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        MediaStorage::delete($brand->brand_image, 'brands');

        if($brand->delete()) {
            return true;
        }
        return false;
    }
}
