<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use App\Support\MediaStorage;

class BlogService
{

    // public function getAllBlog($id)
    // {
    //     return Blog::findOrFail($id);
    // }

    public function update_blog($request, $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->blog_name = $request->blog_name;
        $blog->blog_description = $request->blog_description;
        $blog->blog_category_id = $request->blog_category_id;
        $blog->status = $request->status;

        if ($request->hasFile('blog_image')) {
           
            $fileNameToStore = MediaStorage::replace($request->file('blog_image'), 'blogs', $blog->blog_image);
            $blog->blog_image = $fileNameToStore;
        }
        
        $blog->save();
        return $blog;
    }

    public function getAllblog()
    {
        return Blog::with('blog_category')->orderBy('id', 'desc')
            ->paginate(config('constants.ROW_PER_PAGE'));
    }
    

    public function createBlog($request)
    {

        if ($request->hasFile('blog_image')) {
            $fileNameToStore = MediaStorage::store($request->file('blog_image'), 'blogs');
            
        } else {
            $fileNameToStore = '';
        }
        
        $blog = new Blog([
            'blog_name' => $request->blog_name,
            'blog_category_id' => $request->blog_category_id,
            'blog_image' => $fileNameToStore,
            'blog_description' => $request->blog_description,
            'status' => $request->status
        ]);

        $blog->save();
        return $blog;

    }

    public function get_blog_details($id)
    {
        return Blog::findOrFail($id);
    }


    public function delete_blog($id)
    {
        $blog = Blog::findOrFail($id);
        MediaStorage::delete($blog->blog_image, 'blogs');

        if($blog->delete()) {
            return true;
        }
        return false;
    }
}
