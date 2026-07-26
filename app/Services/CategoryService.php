<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\LeadFormDetail;
use App\Models\Lead;
use App\Support\MediaStorage;

class CategoryService
{

    public function get_category_data($id)
    {
        return Category::findOrFail($id);
    }

    public function update_category($request, $id)
    {
        $category = Category::findOrFail($id);
        $category->category_name = $request->category_name;
        $category->category_slug = $request->category_slug;
        $category->category_description = $request->category_description;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
        $category->is_display_products = $request->boolean('is_display_products');
        $category->is_menu = $request->boolean('is_menu');
        $category->is_slider_bottom = $request->boolean('is_slider_bottom');
        $category->is_feature = $request->boolean('is_feature');

        if ($request->hasFile('category_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('category_image'), 'categories', $category->category_image);
            $category->category_image = $fileNameToStore;
        }
        
        $category->save();
        return $category;
    }

    public function getAllLeadsForms_backup()
    {
        return LeadsForm::leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
                    ->select('leads_form.*', 'parents.form_name as parent_name')
                    ->orderBy('leads_form.id', 'asc')
                    ->paginate(config('constants.ROW_PER_PAGE'));

        /*return LeadsForm::leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
                    ->leftJoin('lead_form_details as lfd', 'leads_form.form_id', '=', 'lfd.form_id')
                    ->select('leads_form.*', 'parents.form_name as parent_name', 'lfd.table_name')
                    ->orderBy('leads_form.id', 'asc')
                    ->groupBy('lfd.form_id')
                    ->paginate(config('constants.ROW_PER_PAGE'));*/
    }

    public function getAllCategory()
    {
        return Category::with('parent')->where('status', '!=', 3)
            ->orderBy('id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));
    }


    public function getAllLeadsForms()
    {
        $leadsForms = LeadsForm::with(['leadFormDetail' => function($query) {
                $query->select('form_id', 'table_name'); // only required columns
            }])
            ->leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
            ->select(
                'leads_form.id',
                'leads_form.form_id',
                'leads_form.form_name',
                'leads_form.form_status',
                'leads_form.parent_id',
                'parents.form_name as parent_name'
            )
            ->groupBy(
                'leads_form.id',
                'leads_form.form_id',
                'leads_form.form_name',
                'leads_form.form_status',
                'leads_form.parent_id',
                'parents.form_name'
            )
            ->orderBy('leads_form.id', 'asc')
            ->paginate(config('constants.ROW_PER_PAGE'));
    
        //process the `table_names` in PHP
        foreach ($leadsForms as $form) {
            $form->table_names = $form->leadFormDetail->pluck('table_name')->unique()->implode(', ');
        }
    
        return $leadsForms;
    }
    

    
    
    public function createCategory($request)
    {
        // $request['form_id'] = str_pad(mt_rand(1, 9999999999), 10);

        if ($request->hasFile('category_image')) {
            $fileNameToStore = MediaStorage::store($request->file('category_image'), 'categories');
            
        } else {
            $fileNameToStore = '';
        }
        
        $category = new Category([
            'category_name' => $request->category_name,
            'category_slug' => $request->category_slug,
            'parent_id' => $request->parent_id,
            'category_image' => $fileNameToStore,
            'category_description' => $request->category_description,
            'is_display_products' => $request->boolean('is_display_products'),
            'is_menu' => $request->boolean('is_menu'),
            'is_slider_bottom' => $request->boolean('is_slider_bottom'),
            'is_feature' => $request->boolean('is_feature'),
            'status' => $request->status
        ]);

        $category->save();
        return $category;

    }

    public function get_category_details($id)
    {
        return Category::findOrFail($id);
    }

    public function updateLeadsForm($id, $data)
    {
        $leadsForm = LeadsForm::findOrFail($id);
        $leadsForm->update($data);
        return $leadsForm;
    }

    public function getLeadsFormParentName($id)
    {
        return LeadsForm::leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
                        ->select('leads_form.*', 'parents.form_name as parent_name')
                        ->where('leads_form.id', $id)
                        ->first();
    }

    public function searchLeadForm_backup($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = LeadsForm::leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
                        ->select('leads_form.*', 'parents.form_name as parent_name');
        $query->where(function($q) use ($searchTerm) {
            $q->where('leads_form.form_name', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function searchLeadForm_live_server_problem($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = LeadsForm::leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
        ->leftJoin('lead_form_details', 'leads_form.form_id', '=', 'lead_form_details.form_id')
        ->select(
            'leads_form.id',
            'leads_form.form_id',
            'leads_form.form_name',
            'leads_form.form_status',
            'leads_form.parent_id',
            'parents.form_name as parent_name',
            DB::raw('GROUP_CONCAT(DISTINCT lead_form_details.table_name) as table_names'),
          
        )
        ->groupBy(
            'leads_form.id',
            'leads_form.form_id',
            'leads_form.form_name',
            'leads_form.form_status',
            'leads_form.parent_id',
            'parents.form_name'
        );
        $query->where(function($q) use ($searchTerm) {
            $q->where('leads_form.form_name', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function searchLeadForm($request)
    {
        $searchTerm = trim($request->input('search'));

        // fetch leads forms with parent and leadFormDetail relationships
        $query = LeadsForm::with(['leadFormDetail' => function ($query) {
            $query->select('form_id', 'table_name'); // Load only required columns
        }])
            ->leftJoin('leads_form as parents', 'leads_form.parent_id', '=', 'parents.form_id')
            ->select(
                'leads_form.id',
                'leads_form.form_id',
                'leads_form.form_name',
                'leads_form.form_status',
                'leads_form.parent_id',
                'parents.form_name as parent_name'
            )
            ->groupBy(
                'leads_form.id',
                'leads_form.form_id',
                'leads_form.form_name',
                'leads_form.form_status',
                'leads_form.parent_id',
                'parents.form_name'
            );

        //search filter
        $query->where(function ($q) use ($searchTerm) {
            $q->where('leads_form.form_name', 'LIKE', '%' . $searchTerm . '%');
        });
        //paginated result
        $leadsForms = $query->paginate(config('constants.ROW_PER_PAGE'));
        //table names in PHP
        foreach ($leadsForms as $form) {
            $form->table_names = $form->leadFormDetail->pluck('table_name')->unique()->implode(', ');
        }
        return $leadsForms;
    }



    public function delete_category($id)
    {
        $category = Category::findOrFail($id);
        MediaStorage::delete($category->category_image, 'categories');

        if($category->delete()) {
            return true;
        }
        return false;
    }
}
