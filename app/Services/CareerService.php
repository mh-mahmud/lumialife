<?php

namespace App\Services;

use App\Models\Career;
use Illuminate\Support\Facades\DB;
use App\Support\MediaStorage;

class CareerService
{

    // public function getAllcareer($id)
    // {
    //     return Career::findOrFail($id);
    // }

    public function update_career($request, $id)
    {
        $career = Career::findOrFail($id);
        $career->job_title = $request->job_title;
        $career->job_description = $request->job_description;
        // $career->career_category_id = $request->career_category_id;
        $career->status = $request->status;

        if ($request->hasFile('job_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('job_image'), 'careers', $career->job_image);
            $career->job_image = $fileNameToStore;
        }
        
        $career->save();
        return $career;
    }

    public function getAllCareer()
    {
        return Career::orderBy('id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));
    }
    

    public function createcareer($request)
    {

        if ($request->hasFile('job_image')) {
            $fileNameToStore = MediaStorage::store($request->file('job_image'), 'careers');
            
        } else {
            $fileNameToStore = '';
        }
        
        $career = new career([
            'job_title' => $request->job_title,
            // 'career_category_id' => $request->career_category_id,
            'job_image' => $fileNameToStore,
            'job_description' => $request->job_description,
            'status' => $request->status
        ]);

        $career->save();
        return $career;

    }

    public function get_career_details($id)
    {
        return Career::findOrFail($id);
    }


    public function delete_career($id)
    {
        $career = Career::findOrFail($id);
        MediaStorage::delete($career->job_image, 'careers');

        if($career->delete()) {
            return true;
        }
        return false;
    }
}
