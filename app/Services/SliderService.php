<?php

namespace App\Services;

use App\Models\Slider;
use App\Support\MediaStorage;

class SliderService
{


    public function getAllSliders()
    {
        return Slider::orderBy('created_at', 'desc')->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function create_slider($request)
    {
        if ($request->hasFile('slider_image')) {
            $fileNameToStore = MediaStorage::store($request->file('slider_image'), 'sliders');
            
        } else {
            
            $fileNameToStore = '';
        }
        
        $slider = new Slider([
            'slider_title' => $request->slider_title,
            'slider_image' => $fileNameToStore,
            'slider_description' => $request->slider_description,
            'status' => $request->status
        ]);

        $slider->save();
        // $user->agent()->save($agent);
        return $slider;
    }

    public function getSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return compact('slider');
    }

    public function getSliderEditData($id)
    {
        $slider = Slider::findOrFail($id);
        return compact('slider');
    }


    public function updateSlider($request, $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->slider_title = $request->slider_title;
        $slider->slider_description = $request->slider_description;
        $slider->status = $request->status;

        if ($request->hasFile('slider_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('slider_image'), 'sliders', $slider->slider_image);
            $slider->slider_image = $fileNameToStore;
               
        }
        $slider->save();
        return $slider;
    }

    public function searchSlider($request)
    {
        $searchTerm = trim($request->input('search'));
        $query = Slider::query();

        $query->where(function($q) use ($searchTerm) {
            $q->where('slider_title', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('slider_image', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('slider_description', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        MediaStorage::delete($slider->slider_image, 'sliders');

        if($slider->delete()) {
            return true;
        }
        return false;
    }
}
