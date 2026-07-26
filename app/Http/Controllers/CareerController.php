<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Blog;
use App\Models\BloggerCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\CareerService;

class CareerController extends Controller {

    protected $career_service;

    public function __construct(CareerService $career_service)
    {
        $this->career_service = $career_service;
    }

    public function index()
    {
        $careers = $this->career_service->getAllCareer();
        return view('careers.index', compact('careers'));
    }

    public function create()
    {
        //$parents = careergerCategory::whereNull('parent_id')->pluck('category_name', 'id');
        return view('careers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:191',
            // 'career_category_id' => 'required',
            'job_description' => 'required',
            'job_image' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $this->career_service->createCareer($request);
        return redirect()->route('career-list')->with('success', 'Career content created successfully.');
    }

    public function show($id)
    {
        $career = $this->career_service->get_career_details($id);
        return view('careers.show', compact('career'));
    }

    public function edit($id)
    {
        $career = $this->career_service->get_career_details($id);
        // $parents = careergerCategory::whereNull('parent_id')->pluck('category_name', 'id');
        return view('careers.edit', compact('career'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'job_title' => 'required|string|max:191',
            // 'career_category_id' => 'required',
            'job_description' => 'required',
            'job_image' => 'nullable|image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $this->career_service->update_career($request, $id);
        return redirect()->route('career-list')->with('success', 'Career updated successfully.');
    }

    public function search(Request $request)
    {
        $searchTerm = trim($request->input('search'));
        if (empty($searchTerm)) {
            return redirect()->route('career-list')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);

        $careers = Career::where('job_title', $searchTerm)->orderBy('id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));
        return view('careers.index', compact('careers'));
    }


    public function destroy($id)
    {
        $this->career_service->delete_career($id);
        return redirect()->route('career-list')->with('success', 'Career deleted successfully.');
    }

    public function update_career_image($id)
    {
        $career = Career::findOrFail($id);
        if ($career->job_image) {
            // path to the image file
            \App\Support\MediaStorage::delete($career->job_image, 'careers');
            //Update the career record to remove the profile image
            $career->job_image = null;
            $career->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'No career image found']);
    }

}
