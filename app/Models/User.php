<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Permissions\HasPermissionsTrait;
use App\Models\Role;
use Auth;
use Session;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'user_id',
        'status',
        'gender',
        'address',
        'profile_image',
        'password',
        'user_type',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userRole()
    {

        return $this->hasMany(UsersRole::class);

    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function get_menu_data($type=null) {
        return Role::where('id', $this->role_id)->value('menu_details');
    }

    public function get_permission_data($type=null) {
        return Role::where('id', $this->role_id)->value('permission_details');
    }


    
    public function hasPermission($permission) {

        $permission_details = $this->get_permission_data();
        
        if ($permission_details) {
            $data = [];
            $permissions = json_decode($permission_details, true);

            foreach($permissions as $keys=>$vals) {
                foreach($vals as $key=>$val) {
                    $data[$key] = 1;
                }
            }

            if(isset($data[$permission])) {
                return true;
            }
        }
        return false;
    }
    
}
