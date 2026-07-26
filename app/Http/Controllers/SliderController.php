<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use Carbon\Carbon;
use App\Services\SliderService;

class SliderController extends Controller
{

    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    public function index()
    {
        
        $sliders = $this->sliderService->getAllSliders();
        return view('sliders.index', compact('sliders'));
    }

    function create() {
        return view('sliders.create');
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'slider_title' => 'required|unique:sliders,slider_title',
            'slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
           
        ]);
       
        // If validation fails,return error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = $this->sliderService->create_slider($request);
        return redirect()->route('slider-list')->with('success', 'Slider created successfully.');
    }

   
    public function show($id)
    {
        $sliderData = $this->sliderService->getSlider($id);
        return view('sliders.show', $sliderData);
    }

   
    public function edit($id)
    {
        $sliderData = $this->sliderService->getSliderEditData($id);
        return view('sliders.edit', $sliderData);
    }


    public function update(Request $request, $id)
    {
        
        $request->validate([
            'slider_title' => 'required|unique:sliders,slider_title,' . $request->id,
            'slider_image' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $this->sliderService->updateSlider($request, $id);
        return redirect()->route('slider-list')->with('success', 'Slider updated successfully.');
    }

  

    public function search(Request $request)
    {

        $searchTerm = trim($request->input('search'));

        if (empty($searchTerm)) {
            return redirect()->route('slider-list')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);
       
        $sliders = $this->sliderService->searchSlider($request);
        return view('slider.index', compact('sliders'));
    }




    public function destroy($id)
    {
        try {
            $this->sliderService->deleteSlider($id);
            return redirect()->route('slider-list')->with('success', 'Slider deleted successfully.');
        } catch (\Exception $e) {
           return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateSliderImage($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->slider_image) {
            // path to the image file
            \App\Support\MediaStorage::delete($slider->slider_image, 'sliders');
            //Update the slider record to remove the profile image
            $slider->slider_image = null;
            $slider->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'No slider image found']);
    }

}
