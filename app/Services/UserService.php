<?php
namespace App\Services;

use App\Support\MediaStorage;

use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\SmsQueue;
use App\Models\SmsLog;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class UserService {

	public function get_all_user() {
		return User::paginate(config('constants.ROW_PER_PAGE'));
	}

    public function get_all_role() {
        return Role::paginate(config('constants.ROW_PER_PAGE'));
    }

    public function create_user($request) {

        $user = User::create([
            'user_id' => str_pad(mt_rand(1, 9999999999999), 20),
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'user_type' =>'user',
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);
        $user->save();
        return $user;
    }

    public function show_user($id) {
        return User::findOrFail($id);
    }

    public function edit_user($request) {
        $user = User::findOrFail($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->role_id = $request->role_id;
        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->status = $request->status;
        $user->address = $request->address;
        if($user->save()) {
                return true;
        }
        return false;
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        if($user->delete()) {
            return true;
        }
        return false;
    }

    public function get_all_permission() {
        return Menu::with('parent')
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->paginate(config('constants.ROW_PER_PAGE'));
    }

    public function syncRoutePermissions(): int
    {
        $fallbackParent = Menu::firstOrCreate(
            ['parent_id' => null, 'sub_name' => 'route-permissions'],
            ['name' => 'Route Permissions', 'show_in_menu' => 0, 'status' => 1]
        );
        $ordersParent = Menu::firstOrCreate(
            ['parent_id' => null, 'name' => 'Orders'],
            ['sub_name' => 'orders', 'show_in_menu' => 1, 'status' => 1]
        );

        $moduleParents = collect([
            'home-page-setting-' => 'Home Page Setting',
            'newsletter-subscribers.' => 'Newsletter Subscribers',
            'outlet-location-' => 'Outlet Locations',
            'product-color-' => 'Product Attributes',
            'product-size-' => 'Product Attributes',
            'shipping-method-' => 'Shipping',
        ])->mapWithKeys(function ($label, $prefix) {
            return [$prefix => Menu::firstOrCreate(
                ['parent_id' => null, 'name' => $label],
                ['sub_name' => Str::slug($label), 'show_in_menu' => 0, 'status' => 1]
            )];
        });

        $created = 0;

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            $middleware = $route->middleware();
            $excluded = $route->excludedMiddleware();

            if (!$name
                || !in_array('check-permission', $middleware, true)
                || in_array('check-permission', $excluded, true)) {
                continue;
            }

            $parent = str_starts_with($name, 'orders-') ? $ordersParent : $fallbackParent;
            foreach ($moduleParents as $prefix => $moduleParent) {
                if (str_starts_with($name, $prefix)) {
                    $parent = $moduleParent;
                    break;
                }
            }
            $permission = Menu::where('sub_name', $name)->first();

            if ($permission) {
                if ($permission->parent_id !== $parent->id) {
                    $permission->parent_id = $parent->id;
                    $permission->save();
                }
                continue;
            }

            Menu::create([
                'parent_id' => $parent->id,
                'name' => Str::headline(str_replace(['.', '-'], ' ', $name)),
                'sub_name' => $name,
                'show_in_menu' => $name === 'orders-index' ? 1 : 0,
                'status' => 1,
            ]);

            $created++;
        }

        return $created;
    }

    public function get_parent_list() {
        return Menu::whereNull('parent_id')->get(['id', 'name']);
    }

    public function create_permission($request) {

        $data = Menu::create([
            'name' => $request->name,
            'sub_name' => $request->slug,
            'show_in_menu' => $request->show_in_menu,
            'parent_id' => !empty($request->parent_id) ? $request->parent_id : null
        ]);
        $data->save();
        return $data;
    }

    public function edit_permission($request) {

        $user = Menu::findOrFail($request->id);
        $user->name = $request->name;
        $user->sub_name = $request->slug;
        $user->show_in_menu = $request->show_in_menu;
        $user->parent_id = !empty($request->parent_id) ? $request->parent_id : null;
        if($user->save()) {
            return true;
        }
        return false;
    }

    public function show_permission($id) {
        return Menu::findOrFail($id);
    }

    public function show_role_data($id) {
        return Role::findOrFail($id);
    }

    public function delete_permission($id) {
        $user = Menu::findOrFail($id);
        if($user->delete()) {
            return true;
        }
        return false;
    }

    public function delete_role($id) {
        $user = Role::findOrFail($id);
        if($user->delete()) {
            return true;
        }
        return false;
    }

    public function menu_list() {
        $data = [];
        $menus = Menu::where('parent_id', '=', null)->get(['id', 'name', 'show_in_menu', 'status']);
        foreach($menus as $key=>$val) {
            $name = str_replace(" ", "_", $val->name);
            $data[$name] = Menu::where('parent_id', $val->id)->get(['id', 'parent_id', 'name', 'sub_name', 'show_in_menu', 'status']);
        }
        return $data;
    }

    public function create_role_data($request) {
        // dd($request->all());

        $role_name = $request->role_name;
        $all_req = $request->all();

        unset($all_req['role_name']);
        unset($all_req['_token']);
        $data_set = [];
        $id_set = [];
        $menu_set = [];

        foreach($all_req as $key=>$val) {
            for($i=0; $i<count($val); $i++) {
                $menu_details = Menu::where('id', $val[$i])->first(['name','sub_name', 'show_in_menu']);
                $data_set[$key][$menu_details->sub_name] = $menu_details->name;
                $id_set[$key][] = $val[$i];

                if($menu_details->show_in_menu==1) {
                    $menu_set[$key][$menu_details->sub_name] = $menu_details->name;
                }
            }
        }

        $json_data = json_encode($data_set);
        $id_data = json_encode($id_set);
        $menu_data = json_encode($menu_set);

        $role = Role::create([
            'name' => $role_name,
            'permission_details' => $json_data,
            'menu_details' => $menu_data,
            'permission_ids' => $id_data,
            'slug' => strtolower(str_replace(" ", "_", $role_name)),
            'status' => 1
        ]);
        //dd($json_data);
        $role->save();
        return $role;
    }

    public function get_all_role_name() {
        $send = [];
        $data = Role::all(['id', 'name']);
        foreach($data as $key=>$value) {
            $send[$value->id] = $value->name;
        }
        return $send;
    }

    public function get_role_data($id) {
        return Role::where('id', $id)->first(['id', 'name', 'permission_ids']);
    }

    public function edit_role_data($request) {

        $role_name = $request->role_name;
        $id = $request->id;
        $all_req = $request->all();

        unset($all_req['role_name']);
        unset($all_req['_token']);
        unset($all_req['id']);
        $data_set = [];
        $id_set = [];
        $menu_set = [];

        foreach($all_req as $key=>$val) {
            for($i=0; $i<count($val); $i++) {
                $menu_details = Menu::where('id', $val[$i])->first(['name','sub_name', 'show_in_menu']);
                $data_set[$key][$menu_details->sub_name] = $menu_details->name;
                $id_set[$key][] = $val[$i];

                if($menu_details->show_in_menu==1) {
                    $menu_set[$key][$menu_details->sub_name] = $menu_details->name;
                }
            }
        }
        $json_data = json_encode($data_set);
        $id_data = json_encode($id_set);
        $menu_data = json_encode($menu_set);

        // dd($menu_data);

        $role = Role::findOrFail($id);
        $role->name = $role_name;
        $role->permission_details = $json_data;
        $role->menu_details = $menu_data;
        $role->permission_ids = $id_data;
        $role->slug = strtolower(str_replace(" ", "_", $role_name));
        $role->status = 1;
        // dd("Loka");
        if($role->save()) {
            return $role;
        }
        return false;
    }

    public function show_user_with_role_backup($id)
    {
        $user = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id', $id)
            ->select('users.*', 'roles.name as role_name')
            ->first();

        return $user;
    }
    public function show_user_with_role($id)
    {

        $user = DB::table('users')->where('id', $id)->first();
        if ($user && !empty($user->role_id)) {
            $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
                ->select('users.*', 'roles.name as role_name')
                ->first();

            return $user;
        }

        return $user;
    }


    public function getUserById($id)
    {
        return User::findOrFail($id);
    }
    

    public function updateUser($id, $request)
{
    $user = User::findOrFail($id);
    $data = $request->all();

    // Validate current password if provided
    if (!empty($data['current_password'])) {
        if (!Hash::check($data['current_password'], $user->password)) {
            throw new \Exception('Current password is incorrect.');
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
    } else {
        unset($data['password']);
    }

    // Handle profile image upload if provided
    if ($request->hasFile('profile_image')) {
        $fileNameToStore = MediaStorage::replace($request->file('profile_image'), 'agents', $user->profile_image);
        $data['profile_image'] = $fileNameToStore;
    }

    $user->update($data);

    return $user;
}

    public function searchUser($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = User::query();
        //dd($query);die();
        $query->where(function ($q) use ($searchTerm) {
            $q->where('username', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('phone_number', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('user_type', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('gender', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('address', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }


    public function searchRole($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = Role::query();
        //dd($query);die();
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', '%' . $searchTerm . '%')
             ;
        });

        return $query->paginate(config('constants.ROW_PER_PAGE'));
    }


    public function searchPermission($request)
    {
        $searchTerm = trim($request->input('search'));

        $query = Menu::query();
        //dd($query);die();
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('sub_name', 'LIKE', '%' . $searchTerm . '%');
        });

        return $query->with('parent')->paginate(config('constants.ROW_PER_PAGE'));
    }
}
