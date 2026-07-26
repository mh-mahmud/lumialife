<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Agent;
use App\Models\Settings;
use App\Support\MediaStorage;
use Auth;

class UserController extends Controller
{
    protected $service;
    public function __construct(UserService $service) {
    	$this->service = $service;
    }

    public function index() {
    	$data = [];
    	// dd(Auth::user()->get_menu_data());
    	$data['users'] = $this->service->get_all_user();
    	$data['role_names'] = $this->service->get_all_role_name();
    	return view('users.user_list', $data);
    }

    public function create() {
    	$data = [];
    	$data['role_list'] = $this->service->get_all_role();
    	return view('users.create_user', $data);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string'
        ]);
       
        $user = $this->service->create_user($request);
        if(!empty($user->id)) {
        	return redirect()->to('user-list')->with('success', 'User created successfully.');
        }
        return redirect()->route('create-user')->with('error', 'Failed request');
    }

    public function edit_form($id) {
    	$data = [];
    	$data['role_list'] = $this->service->get_all_role();
    	$data['user_data'] = $this->service->show_user($id);
    	return view('users.edit', $data);
    }

    public function update(Request $request) {
    	// dd($request->all());
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->id,
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $request->id
           
        ]);
       
        $user = $this->service->edit_user($request);
        if($user) {
        	return redirect()->to('user-list')->with('success', 'User edited successfully.');
        }
        return redirect()->back()->with('error', 'Failed request');
    }

    public function show($id) {
    	$res = [];
    	$res['user'] = $this->service->show_user($id);
    	return view('users.show', $res);
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteUser($id);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // permission data
    public function permission_index() {
    	$data = [];
    	$data['users'] = $this->service->get_all_permission();
    	return view('users.permission_list', $data);
    }

    public function permission_create() {
    	$data = [];
    	$data['list'] = $this->service->get_parent_list();
    	return view('users.create_permission', $data);
    }

    public function permission_store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|unique:permissions',
            // 'slug' => 'required|unique:permissions',
            'show_in_menu' => 'required'
        ]);

        // dd($request->all());

        $user = $this->service->create_permission($request);
        if(!empty($user->id)) {
        	return redirect()->to('permission-list')->with('success', 'Permission created successfully.');
        }
        return redirect()->route('permission-user')->with('error', 'Failed request');
    }

    public function permission_update(Request $request) {
        $request->validate([
            'name' => 'required',
            'show_in_menu' => 'required'
        ]);
        $data = $this->service->edit_permission($request);
        if($data) {
        	return redirect()->to('permission-list')->with('success', 'Permission edited successfully.');
        }
        return redirect()->back()->with('error', 'Failed request');
    }

    public function permission_edit($id) {
    	$res = [];
    	$res['list'] = $this->service->get_parent_list();
    	$res['data'] = $this->service->show_permission($id);
    	return view('users.permission_edit', $res);
    }

    public function permission_show($id) {
    	$res = [];
    	$res['user'] = $this->service->show_permission($id);
    	return view('permission.show', $res);
    }

    public function permission_destroy($id)
    {
        try {
            $this->service->delete_permission($id);
            return redirect()->route('permission.index')->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function role_index() {
    	$data = [];
    	$data['roles'] = $this->service->get_all_role();
    	return view('users.role_list', $data);
    }

    public function role_destroy($id) {
        try {
            $this->service->delete_role($id);
            return redirect()->route('role-list')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function role_create() {
    	$data = [];
    	$data['menus'] = $this->service->menu_list();
    	return view('users.create_role', $data);
    }

    public function role_store(Request $request) {
        $request->validate([
            'role_name' => 'required'
        ]);
    	// dd($request->all());

        $role_data = $this->service->create_role_data($request);
        // dd($role_data);
        if(!empty($role_data)) {
        	return redirect()->to('role-list')->with('success', 'Role created successfully.');
        }
        return redirect()->back()->with('error', 'Failed request');
    }

    public function role_edit($id) {
        $data = [];
        $ids = [];
        $data['menus'] = $this->service->menu_list();
        $data['role_data'] = $this->service->get_role_data($id);
        $permissions = json_decode($data['role_data']->permission_ids);
        if(!empty($permissions)) {
            foreach($permissions as $key=>$val) {
                for($i=0; $i<count($val); $i++) {
                    $ids[] = $val[$i];
                }
            }
            $data['ids'] = $ids;
        }
        else {
            $data['ids'] = [];
        }

        
        return view('users.edit_role', $data);
    }

    public function role_update(Request $request) {
        // dd($request->all());
        $request->validate([
            'role_name' => 'required'
        ]);

        $role_data = $this->service->edit_role_data($request);
        // dd($role_data);
        if(!empty($role_data)) {
            return redirect()->to('role-list')->with('success', 'Role created successfully.');
        }
        return redirect()->back()->with('error', 'Failed request');
    }

  

    public function user_profile($id)
    {
        $user = $this->service->show_user_with_role($id);
        return view('users.user_profile', compact('user'));
    }


    public function profile_edit($id)
    {
        $user = $this->service->getUserById($id);
        return view('users.account_settings', compact('user'));
    }



    public function profile_update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:6',
            'profile_image' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'password_confirmation' => 'required_with:password',
        ]);

        try {
            //$this->service->updateUser($id, $request);
            $user = $this->service->updateUser($id, $request);
            // Update session data
            Session::setId(session()->getId());
            Session::put('users', $user);
            return redirect()->back()->with('success', 'Account settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function search(Request $request)
    {
        $searchTerm = trim($request->input('search'));

        if (empty($searchTerm)) {
            return redirect()->route('users.index')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);

        $users = $this->service->searchUser($request);
        return view('users.user_list', compact('users'));
    }


    public function role_search(Request $request)
    {
        $searchTerm = trim($request->input('search'));

        if (empty($searchTerm)) {
            return redirect()->route('role-list')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);

        $roles = $this->service->searchRole($request);
        return view('users.role_list', compact('roles'));
    }


    public function permission_search(Request $request)
    {
        $searchTerm = trim($request->input('search'));

        if (empty($searchTerm)) {
            return redirect()->route('permission.index')->with('error', 'Search Field cannot be blank.');
        }

        $request->validate([
            'search' => 'required|string',
        ]);

        $users = $this->service->searchPermission($request);
        return view('users.permission_list', compact('users'));
    }

    public function deleteProfileImage($id)
    {
        $user = User::find($id);

        MediaStorage::delete($user?->profile_image, 'agents');
        $user->profile_image = null;

        $user->save();

        //return redirect()->back();
    }


    public function updateProfileImage($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile_image) {
            // path to the image file
            MediaStorage::delete($user->profile_image, 'agents');
            //Update the user record to remove the profile image
            $user->profile_image = null;
            $user->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'No profile image found']);
    }

    public function role_show($id) {
        $res = [];
        $res['user'] = $this->service->show_role_data($id);
        return view('users.show_roles', $res);
    }

    public function app_settings() {
        $data = Settings::first();
        return view('users.app_settings', compact('data'));
    }

    public function store_app_settings(Request $request) {

        $settings = Settings::findOrFail($request->id);
        $request->validate([
            'facebook_link' => ['nullable', 'url', 'max:2048'],
            'whats_app_link' => ['nullable', 'url', 'max:2048'],
            'instagram_link' => ['nullable', 'url', 'max:2048'],
            'youtube_link' => ['nullable', 'url', 'max:2048'],
            'twitter_link' => ['nullable', 'url', 'max:2048'],
            'linkedin_link' => ['nullable', 'url', 'max:2048'],
            'office_phone_number' => ['nullable', 'string', 'max:50'],
            'phone_number_2' => ['nullable', 'string', 'max:50'],
            'phone_number_3' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:10000'],
            'footer_message' => ['nullable', 'string', 'max:1000'],
            'sidebar_image_01' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'sidebar_image_02' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'about_us_img' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'site_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,gif,webp', 'max:2048'],
            'app_promo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'app_promo_link' => ['nullable', 'string', 'max:2048', 'regex:/^(\/(?!\/)|https?:\/\/)/i'],
            'app_promo_enabled' => ['required', 'boolean'],
            'myr_to_bdt_rate' => ['required', 'numeric', 'gt:0', 'max:99999999.9999'],
        ]);

        $sidebar_image_01 = $settings->sidebar_image_01;
        $sidebar_image_02 = $settings->sidebar_image_02;
        $about_us_img = $settings->about_us_img;
        $site_logo = $settings->site_logo;
        $favicon = $settings->favicon;
        $app_promo_image = $settings->app_promo_image;
        if ($request->hasFile('sidebar_image_01')) {
            $sidebar_image_01 = MediaStorage::replace($request->file('sidebar_image_01'), 'settings', $settings->sidebar_image_01, '');
        }
        if ($request->hasFile('sidebar_image_02')) {
            $sidebar_image_02 = MediaStorage::replace($request->file('sidebar_image_02'), 'settings', $settings->sidebar_image_02, '');
        }
        
        if ($request->hasFile('about_us_img')) {
            $about_us_img = MediaStorage::replace($request->file('about_us_img'), 'settings', $settings->about_us_img, '');
        }
        
        if ($request->hasFile('site_logo')) {
            $site_logo = MediaStorage::replace($request->file('site_logo'), 'settings', $settings->site_logo, '');
        }

        if ($request->hasFile('favicon')) {
            $favicon = MediaStorage::replace($request->file('favicon'), 'settings', $settings->favicon, '');
        }

        if ($request->hasFile('app_promo_image')) {
            $app_promo_image = MediaStorage::replace($request->file('app_promo_image'), 'settings', $settings->app_promo_image, '');
        }

        $settings->facebook_link = $request->facebook_link;
        $settings->whats_app_link = $request->whats_app_link;
        $settings->instagram_link = $request->instagram_link;
        $settings->youtube_link = $request->youtube_link;
        $settings->twitter_link = $request->twitter_link;
        $settings->linkedin_link = $request->linkedin_link;
        $settings->messanger_link = $request->messanger_link;
        $settings->whats_app_chat_link = $request->whats_app_chat_link;
        $settings->google_map_link = $request->google_map_link;
        $settings->office_phone_number = $request->office_phone_number;
        $settings->phone_number_2 = $request->phone_number_2;
        $settings->phone_number_3 = $request->phone_number_3;
        $settings->charge_inside_dhaka = $request->charge_inside_dhaka;
        $settings->charge_outside_dhaka = $request->charge_outside_dhaka;
        $settings->about_us = $request->about_us;
        $settings->contact_address = $request->contact_address;
        $settings->return_policy = $request->return_policy;
        $settings->refund_policy = $request->refund_policy;
        $settings->terms_and_conditions = $request->terms_and_conditions;
        $settings->footer_message = $request->footer_message;
        $settings->faq = $request->faq;
        $settings->sidebar_image_01 = $sidebar_image_01;
        $settings->sidebar_image_02 = $sidebar_image_02;
        $settings->about_us_img = $about_us_img;
        $settings->site_logo = $site_logo;
        $settings->favicon = $favicon;
        $settings->app_promo_image = $app_promo_image;
        $settings->app_promo_link = $request->app_promo_link;
        $settings->app_promo_enabled = $request->boolean('app_promo_enabled');
        $settings->myr_to_bdt_rate = $request->myr_to_bdt_rate;
        $settings->save();
        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    
}
