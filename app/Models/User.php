<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    protected $fillable = ['id','company', 'employee_id','employee_id_hidden', 'name', 'phone', 'email', 'dept_id', 'status', 'designation_id', 'branch_id', 'joining_date','birthdate', 'profile_pic', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at','blood_id','phone_2','email_2','father_name','mother_name','home_no','village','word_no','union','city','sub-district','district','division','capital','country'];

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
    public function getCompanyIdAttribute()
    {
        return session('company_id');
    }

    public function permissions()
    {
        return $this->hasMany(PermissionUser::class,'user_id','id');
    }


    public function hasPermission($permission)
    {
        // Implement your permission check logic here
        return $this->isSystemSuperAdmin()|| $this->defaultPermissions()->where('role_name',$this->getUserType())->where('permission_name',$permission)->exists() || $this->permissions()->where('permission_name', $permission)->exists();
    }
    public function isSuperAdmin()
    {
        // Check if the user is a superadmin (you define the logic for this)
        return $this->getUserType() === 'superadmin'; // Adjust this based on your implementation
    }
    public function isSystemSuperAdmin()
    {
        return $this->getUserType() === 'systemsuperadmin';
    }
    public function getUserType()
    {
        if (Auth::user() !== null) {
            $company_permission = UserCompanyPermission::with(['userRole','companies'])->where('user_id',Auth::user()->id)->first();
            if (isset($company_permission->userRole))
            {
                return $company_permission->userRole->name;
            }
            else
            {
                $roles = $this->roles;

                // Assuming a user has only one role, you can return its name
                if ($roles->count() > 0) {
                    return $roles->first()->name;
                }

                return 'User'; // Default user type if no role is associated
            }
        }else
        {
            $roles = $this->roles;

            // Assuming a user has only one role, you can return its name
            if ($roles->count() > 0) {
                return $roles->first()->name;
            }

            return 'User'; // Default user type if no role is associated
        }

    }
//    public function roles()
//    {
//        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
//    }
    public function department()
    {
        return $this->belongsTo(department::class,'dept_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class,'designation_id');
    }
    public function branch()
    {
        return $this->belongsTo(branch::class,'branch_id');
    }

    public function getSalaryCertificateList()
    {
        return $this->hasMany(UserSalaryCertificateData::class,'user_id');
    }
    public function createdSalaryCertificate()
    {
        return $this->hasMany(UserSalaryCertificateData::class,'created_by');
    }
    public function updatedSalaryCertificate()
    {
        return $this->hasMany(UserSalaryCertificateData::class,'updated_by');
    }
    public function getCompany()
    {
        return $this->belongsTo(company_info::class,'company','id');
    }
//    public function company()
//    {
//        return $this->belongsTo(company_info::class,'company','id');
//    }
    public function companyInfo()
    {
        return $this->belongsTo(company_info::class,'company','id');
    }
//    public function voucherTypeCreate()
//    {
//        return $this->hasMany(VoucherType::class,'created_by');
//    }
}
