<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LeadsForm;
use App\Services\CategoryService;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $cat_service;

    public function __construct(CategoryService $cat_service)
    {
        $this->cat_service = $cat_service;
    }

    public function index()
    {
        $cats = $this->cat_service->getAllCategory();
        return view('category.index', compact('cats'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->pluck('category_name', 'id');
        return view('category.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->merge(['category_slug' => Str::slug($request->category_slug)]);

        $request->validate([
            'category_name' => 'required|string|max:191',
            'category_slug' => 'required|string|max:191|unique:categories,category_slug',
            'category_description' => 'nullable|string',
            'category_image' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
            'is_menu' => 'nullable|boolean',
            'is_slider_bottom' => 'nullable|boolean',
            'is_feature' => 'nullable|boolean',
        ]);
        $this->cat_service->createCategory($request);

        return redirect()->route('category-list')->with('success', 'Category created successfully.');
    }

    public function show($id)
    {
        $category = $this->cat_service->get_category_details($id);
        return view('category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = $this->cat_service->get_category_data($id);
        $parents = Category::whereNull('parent_id')->pluck('category_name', 'id');
        return view('category.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $request->merge(['category_slug' => Str::slug($request->category_slug)]);

        $request->validate([
            'category_name' => 'required|string|max:191',
            'category_slug' => ['required', 'string', 'max:191', Rule::unique('categories', 'category_slug')->ignore($id)],
            'category_description' => 'nullable|string',
            'category_image' => 'image|mimes:avif,jpeg,png,jpg,gif,webp|max:2048',
            'is_menu' => 'nullable|boolean',
            'is_slider_bottom' => 'nullable|boolean',
            'is_feature' => 'nullable|boolean',
        ]);

        $this->cat_service->update_category($request, $id);
        return redirect()->route('category-list')->with('success', 'Category updated successfully.');
    }

    public function search(Request $request)
    {
        $searchTerm = trim($request->input('search'));
        $formName = LeadsForm::whereNull('parent_id')->pluck('form_name', 'form_id');

        if (empty($searchTerm)) {
            return redirect()->route('leadsform-index')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);

        $leadsForms = $this->cat_service->searchLeadForm($request);
        $totalLeadsCounts = $this->getTotalLeadsCountsForForms($leadsForms);
        return view('category.index', compact('leadsForms', 'formName', 'totalLeadsCounts'));
    }


    public function destroy($id)
    {
        $this->cat_service->delete_category($id);
        return redirect()->route('category-list')->with('success', 'Category deleted successfully.');
    }

    private function getTotalLeadsCountsForForms_backup($leadsForms)
    {
        $totalLeadsCounts = [];

        foreach ($leadsForms as $form) {
            $tableNames = explode(',', $form->table_names);
            $totalCount = 0;

            foreach ($tableNames as $tableName) {
                // Chk tableName is not empty before query table data
                if (!empty($tableName)) {
                    $totalCount += DB::table($tableName)->count();
                }
            }

            $totalLeadsCounts[$form->form_id] = $totalCount;
        }

        return $totalLeadsCounts;
    }

    private function getTotalLeadsCountsForForms($leadsForms)
    {
        $totalLeadsCounts = [];

        foreach ($leadsForms as $form) {
            //total number of leads for this form_id
            $leadCount = DB::table('leads')->where('form_id', $form->form_id)->count();
            //store the count in the array with form_id as the key wise
            $totalLeadsCounts[$form->form_id] = $leadCount;
        }

        return $totalLeadsCounts;
    }

    public function updatecategoryImage($id)
    {
        $category = Category::findOrFail($id);
        if ($category->category_image) {
            // path to the image file
            \App\Support\MediaStorage::delete($category->category_image, 'categories');
            //Update the category record to remove the profile image
            $category->category_image = null;
            $category->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'No category image found']);
    }

    public function toggleHomePromo($id)
    {
        $category = Category::findOrFail($id);

        if (! $category->is_home_promo && ! $category->category_image) {
            return redirect()->route('category-list')
                ->with('error', 'Add a category image before enabling the home promo section.');
        }

        DB::transaction(function () use ($category) {
            if (! $category->is_home_promo) {
                Category::where('is_home_promo', 1)->update(['is_home_promo' => 0]);
                $category->is_home_promo = true;
            } else {
                $category->is_home_promo = false;
            }

            $category->save();
        });

        return redirect()->route('category-list')
            ->with('success', $category->is_home_promo
                ? 'Home promo category enabled.'
                : 'Home promo category disabled.');
    }

}
