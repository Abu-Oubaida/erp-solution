<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id', 'employee_id', 'name', 'phone', 'email', 'dept_id', 'status', 'designation', 'branch_id', 'joining_date', 'profile_pic', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'];

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
    ];

    public function permissions()
    {
        return $this->hasMany(PermissionUser::class);
    }
    public function hasPermission($permission)
    {
        // Implement your permission check logic here
        return $this->isSuperAdmin() || $this->permissions()->where('permission_name', $permission)->exists();
    }
    public function isSuperAdmin()
    {
        // Check if the user is a superadmin (you define the logic for this)
        return $this->getUserType() === 'superadmin'; // Adjust this based on your implementation
    }
    public function getUserType()
    {
        $roles = $this->roles;

        // Assuming a user has only one role, you can return its name
        if ($roles->count() > 0) {
            return $roles->first()->name;
        }

        return 'User'; // Default user type if no role is associated
    }

    public function getDepartment()
    {
        return $this->belongsTo(department::class,'dept_id');
    }
    public function getBrance()
    {
        return $this->belongsTo(branch::class,'branch_id');
    }
//    public function voucherTypeCreate()
//    {
//        return $this->hasMany(VoucherType::class,'created_by');
//    }
}
