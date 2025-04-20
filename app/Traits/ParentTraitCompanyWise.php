<?php

namespace App\Traits;

use App\Models\Account_voucher;
use App\Models\BloodGroup;
use App\Models\branch;
use App\Models\BranchType;
use App\Models\company_info;
use App\Models\CompanyModulePermission;
use App\Models\Data_archive_storage_package;
use App\Models\department;
use App\Models\Designation;
use App\Models\Document_requisition_info;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_opening_balance;
use App\Models\Fixed_asset_opening_with_spec;
use App\Models\fixed_asset_specifications;
use App\Models\Fixed_asset_transfer;
use App\Models\Fixed_asset_transfer_with_spec;
use App\Models\Op_reference_type;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompanyPermission;
use App\Models\userProjectPermission;
use App\Models\Voucher_type_permission_user;
use App\Models\VoucherType;
use Illuminate\Database\Eloquent\Builder;
use Log;

trait ParentTraitCompanyWise
{
    use AuthTrait, PermissionTrait;
    public function getSelfInfo()
    {
        return User::with(['permissions','department','designation','branch','getCompany','companyPermissions','roles'])->where('id',$this->user->id)->first();
    }
    public function getUser($operation_permission_name)
    {
        $object = User::with(['permissions','department','designation','branch','getCompany','companyPermissions'])->where('status',1);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getUserAll()
    {
        $object = User::with(['permissions','department','designation','branch','getCompany']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company',$this->getUserCompanyPermissionsArray());
    }
    public function getBranch($operation_permission_name)
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getProject()
    {
        $object = branch::with(['branchType','createdBy','updatedBy','company']);
        if($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }
    public function getBranchType($operation_permission_name)
    {
        $object = BranchType::with(['getBranches','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getDepartment($operation_permission_name)
    {
         $object = department::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    public function getDesignation($operation_permission_name)
    {
        $object = Designation::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    public function getRole($operation_permission_name)
    {
        $object = Role::with(['createdBy','updatedBy','getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name))->where('id','>=',$this->user->roles()->first()->id);
    }

    public function getCompanyModulePermissionWise($operation_permission_name)
    {
        $permission = permission::where('name',$operation_permission_name)->first();
        $companyModulePermission = CompanyModulePermission::whereIn('company_id',$this->getUserCompanyPermissionsArray())->where('module_id',$permission->id)->pluck('company_id')->unique()->toArray();

        $userCompanyPermission = UserCompanyPermission::where('user_id',$this->user->id)->get();
        $companyPermissionIDs = [];
        foreach ($userCompanyPermission as $value)
        {
            if ($value->users->where('id',$this->user->id)->first()->companyWiseRoles()->first()->name == 'superadmin')
            {
                $companyPermissionIDs[] = $value->company_id;
            }
        }
        if ($this->user->isSuperAdmin())
        {
            $companyPermissionIDs[] = $this->user->company;
        }
        $otherCompanyPermissionIDs = PermissionUser::whereIn('company_id',$companyModulePermission)->where('user_id',$this->user->id)->where('permission_name',$operation_permission_name)->pluck('company_id')->unique()->toArray();
        $companyPermissionIDs = array_merge($companyPermissionIDs,$otherCompanyPermissionIDs);
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('id',$this->getUserCompanyPermissionsArray())->whereIn('id',$companyPermissionIDs);
    }
    // gives company id's to user whom have permission
    public function getCompanyModulePermissionWiseArray($operation_permission_name)
    {
        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
//        return $this->getCompanyModulePermissionWise($operation_permission_name)->pluck('id')->unique()->toArray();
    }
    public function getCompany()
    {
        $object = company_info::with(['createdBy','updatedBy','users','companyType','fixedAssets','permissionUsers']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('id',$this->getUserCompanyPermissionsArray());
    }
    public function getUserCompanyPermissionsArray()
    {
        $userComPerIDs = [];
        $userCompanyPermissions = UserCompanyPermission::where('user_id',$this->user->id)->get();
        if (count($userCompanyPermissions) > 0) {
            $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
        }
        $userComPerIDs[] = (integer)$this->user->company_id;
        return $userComPerIDs;
    }
    public function companyWisePermissionUsers($company_id,$operation_permission_name)
    {
        $userIDs = $this->companyWisePermissionUserArray($company_id,$operation_permission_name);
        return $this->getUser($operation_permission_name)->whereIn('id',$userIDs)->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'systemsuperadmin');
//                ->orWhere('name', 'systemadmin'); // Add other roles if needed
        });
    }
    public function companyWisePermissionUserArray($company_id,$operation_permission_name): array
    {
        $userCompanyPermissions = userCompanyPermission::where('company_id', $company_id)->pluck('user_id')->unique()->toArray();
        $users = $this->getUser($operation_permission_name)->where('company',$company_id)->pluck('id')->unique()->toArray();
        return array_merge($userCompanyPermissions,$users);
    }
    public function getUserProjectPermissions($user_id,$permission)
    {
        $object = branch::with(['getUsers','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        $user = User::where('id',$user_id)->first();
        if ($user->companyWiseRoleName() == 'superadmin')
        {
            return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission));
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->whereIn('id',$this->userProjectPermissionArray($user_id));
    }
    public function userProjectPermissionArray($user_id)
    {
        return userProjectPermission::where('user_id',$user_id)->get()->pluck('project_id')->unique()->toArray();
    }
    public function getFixedAssets($operation_permission_name)
    {
        $object = Fixed_asset::with(['company','specifications','createdBy','updatedBy','withRefUses','transfer']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetSpecification($operation_permission_name)
    {
        $object = fixed_asset_specifications::with(['company','fixed_asset','createdBy','createdBy','fixedWithRefData']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetWithRefData($operation_permission_name)
    {
        $object = Fixed_asset_opening_balance::with(['withSpecifications','attestedDocuments','branch','createdBy','updatedBy','refType','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetWithRefSpecification($operation_permission_name)
    {
        $object = Fixed_asset_opening_with_spec::with(['asset','fixed_asset_opening_balance','specification','createdBy','updatedBy','company']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }

    public function getUserCompanyPermissionArray($user_id)
    {
        $userComPerIDs = [];
        $userCompanyPermissions = UserCompanyPermission::where('user_id',$user_id)->get();
        if (count($userCompanyPermissions) > 0) {
            $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
        }
        $userDefaultCompanyID = $this->getUserAll()->where('id',$user_id)->first('company');
        $userComPerIDs[] = (integer)$userDefaultCompanyID->company;
        return $userComPerIDs;
    }
    public function getBloodGroup()
    {
        return BloodGroup::all();
    }

    public function getOperationReferenceType()
    {
        $object = Op_reference_type::with(['createdBy','updatedBy','company','fixedAssetOpening']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('company_id',$this->getUserCompanyPermissionsArray());
    }

    public function getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id): Builder
    {
        return Fixed_asset_opening_with_spec::with(['fixed_asset_opening_balance','specification','asset'])->whereHas('fixed_asset_opening_balance', function ($query) use ($company_id, $projects_ids) {
            $query->where('company_id', $company_id)->where('status',1);
            $query->whereIn('branch_id', $projects_ids);
        });
    }
    public function getProjectWiseTransferMaterialsIn($operation_permission_name,$to_projects_ids,$to_company_id): Builder
    {
        return Fixed_asset_transfer_with_spec::with(['fixed_asset_transfer','specification','asset'])->whereHas('fixed_asset_transfer', function ($query) use ($to_company_id, $to_projects_ids) {
            $query->where('to_company_id', $to_company_id);
            $query->whereIn('to_company_id', $to_projects_ids);
        });
    }
    public function getProjectWiseTransferMaterialsOut($operation_permission_name,$from_projects_ids,$from_company_id): Builder
    {
        return Fixed_asset_transfer_with_spec::with(['fixed_asset_transfer','specification','asset'])->whereHas('fixed_asset_transfer', function ($query) use ($from_company_id, $from_projects_ids) {
            $query->where('from_company_id', $from_company_id);
            $query->where('from_project_id', $from_projects_ids);
//                ->where('status','>=',1);
        });
    }
    public function getProjectWiseTransferMaterialsStock($operation_permission_name,$to_projects_ids,$to_company_id): Builder
    {
        return $this->getProjectWiseTransferMaterialsIn($operation_permission_name,$to_projects_ids,$to_company_id)->whereHas('fixed_asset_transfer', function ($query) use ($to_company_id, $to_projects_ids) {
            $query->where('status','>=',1);
        });
    }

    public function getFixedAssetStockMaterials($operation_permission_name,$projects_ids,$company_id)
    {
        $fixed_asset_ids = [];
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id)->pluck('asset_id')->unique()->toArray();
        $transfer = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$projects_ids,$company_id)->pluck('asset_id')->unique()->toArray();
        $fixed_asset_ids = array_merge($withRef,$transfer);
        // $issue_return--------
        // $mrf or $mpr---------
        return $fixed_asset_ids;
    }
    public function getFixedAssetAllProjectWise($operation_permission_name,$projects_ids,$company_id)
    {
        $fixed_asset_ids = [];
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id)->pluck('asset_id')->unique()->toArray();
        $transfer = Fixed_asset_transfer_with_spec::with(['fixed_asset_transfer'])->whereHas('fixed_asset_transfer', function ($query) use ($company_id, $projects_ids) {
            $query->where('status','>=',1);
            $query->where(function ($query) use ($company_id, $projects_ids) {
                $query->where('from_company_id', $company_id);
                $query->whereIn('from_project_id', $projects_ids);
            });
            $query->orWhere(function ($query) use ($company_id, $projects_ids) {
                $query->where('to_company_id', $company_id);
                $query->whereIn('to_project_id', $projects_ids);
            });
        })->pluck('asset_id')->unique()->toArray();
        $fixed_asset_ids = array_merge($withRef,$transfer);
        // $issue_return--------
        // $mrf or $mpr---------
        return $fixed_asset_ids;
    }
    public function getFixedAssetStockMaterialSpecifications($operation_permission_name,$materials_ids,$projects_ids,$company_id)
    {
        $fixed_asset_specification_id = [];
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->pluck('spec_id')->unique()->toArray();
        $transfer = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->pluck('spec_id')->unique()->toArray();
        $fixed_asset_specification_id = array_merge($withRef,$transfer);
        // $issue_return--------
        // $mrf or $mpr---------
        return $fixed_asset_specification_id;
    }
    public function getFixedAssetSpecificationWiseStockBalance($operation_permission_name,$specification_id,$materials_ids,$projects_ids,$company_id)
    {
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->where('spec_id',$specification_id)->sum('qty');
        $transferIn = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->where('spec_id',$specification_id)->sum('qty');
        $transferOut = $this->getProjectWiseTransferMaterialsOut($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->where('spec_id',$specification_id)->sum('qty');
        $transferStock = ($transferIn - $transferOut);
        // $issue_return--------
        // $mrf or $mpr---------
        return (float) ($withRef+$transferStock);
    }

    public function getFixedAssetSpecificationWiseRate($operation_permission_name,$specification_id,$materials_ids,$projects_ids,$company_id)
    {
        $count = 0;
        $withRef = $this->getProjectWiseWithReferenceMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->where('spec_id',$specification_id)->avg('rate');
        if ($withRef > 0)
        {
            $count++;
        }
        $transferIn = $this->getProjectWiseTransferMaterialsStock($operation_permission_name,$projects_ids,$company_id)->whereIn('asset_id',$materials_ids)->avg('rate');
        if ($transferIn > 0)
        {
            $count++;
        }
        return (float) (($withRef+$transferIn)/$count);
    }
    public function getFixedAssetGpAll($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->where(function ($query) use ($operation_permission_name) {
            $query->whereIn('to_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
            $query->orWhereIn('from_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
        });
    }
    public function getFixedAssetGpDataIn($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object->whereIn('to_company_id',$this->getCompany()->pluck('id')->unique()->toArray());
        }
        return $object->whereIn('to_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetGpDataOut($operation_permission_name)
    {
        $object = Fixed_asset_transfer::with(['specifications','documents','branchFrom','branchTo','createdBy','updatedBy','companyFrom','companyTo','specificationsByReference']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object->whereIn('from_company_id',$this->getCompany()->pluck('id')->unique()->toArray());
        }
        return $object->whereIn('from_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name));
    }
    public function getFixedAssetGpSpecificationAll($operation_permission_name)
    {
        return Fixed_asset_transfer_with_spec::with(['specification','asset','fixed_asset_transfer','createdBy','updatedBy']);
    }

    public function documentRequisition($operation_permission_name)
    {
        $object = Document_requisition_info::with(['receiverUser','sanderCompany','receiverCompany','sender','receivers','attachmentInfos']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $object;
        }
        return $object->whereIn('sander_company_id',$this->getCompanyModulePermissionWiseArray($operation_permission_name))->whereIn('receiver_company_id',$this->getCompany());
    }
    // public function companyDepartmentWithUsers($ids,$request,$company_id){
    //     return department::with(['getUsers'primaryCompany => function ($query) use ($ids, $company_id) {
    //         $query->whereIn('dept_id', $ids) // Filtering users by department IDs
    //               ->where('company', $company_id); // Filtering users by company ID
    //     }])->get();
    // }
    private function getCompanyWiseDataTypes($company_id)
    {
        if ($this->user->isSystemSuperAdmin() || $this->user->companyWiseRoleName() == 'superadmin')
        {
            if ($company_id == null)
            {
                $userWiseVoucherTypePermissionId = VoucherType::all()->pluck('id')->toArray();
            }
            else {
                $userWiseVoucherTypePermissionId = VoucherType::where('company_id',$company_id)->get()->pluck('id')->toArray();
            }
        }
        else
        {
            if ($company_id == null)
            {
                $userWiseVoucherTypePermissionId = Voucher_type_permission_user::where('user_id',$this->user->id)->get()->pluck('voucher_type_id')->toArray();
            }
            else {
                $userWiseVoucherTypePermissionId = Voucher_type_permission_user::where('company_id',$company_id)->where('user_id',$this->user->id)->get()->pluck('voucher_type_id')->toArray();
            }
        }
        if ($company_id == null)
        {
            return VoucherType::where('status',1)->whereIn('id',$userWiseVoucherTypePermissionId)->get();
        }
        else{
            return VoucherType::where('company_id',$company_id)->where('status',1)->whereIn('id',$userWiseVoucherTypePermissionId)->get();
        }

    }
    private function archiveTypeList($permission)
    {
        $voucherTypes = VoucherType::withCount(['voucherWithUsers','archiveDocuments','archiveDocumentInfos'])->with(['voucherWithUsers','createdBY','updatedBY','company','archiveDocumentInfos','archiveDocuments']);
        if ($this->user->isSystemSuperAdmin())
        {
            return $voucherTypes;
        }
        else
        {
            $voucherTypes = $voucherTypes->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission));
            if ($this->user->companyWiseRoleName() == 'superadmin')
            {
                return $voucherTypes;
            }
            else {
                $voucherTypeUserPermissions = Voucher_type_permission_user::where('user_id',$this->user->id)->pluck('voucher_type_id')->toArray();
                $permittedProjectIds = $this->getUserProjectPermissions($this->user->id, $permission)->pluck('id')->toArray();
//                dd($permittedProjectIds);
                $archive_type_lists = $voucherTypes->whereHas('archiveDocumentInfos', function ($query) use ($permittedProjectIds) {
                    $query->whereIn('project_id', $permittedProjectIds);
                })->whereIn('id',$voucherTypeUserPermissions);
                return $archive_type_lists;
            }
        }
    }
    public function getArchiveList($permission)
    {
        $permittedProjectIds = $this->getUserProjectPermissions($this->user->id, $permission)->pluck('id')->toArray();
        $data = Account_voucher::with(['VoucherDocument','VoucherType','createdBY','updatedBY','voucherDocuments'])
            ->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))
            ->whereIn('project_id', $permittedProjectIds)
            ->whereIn('voucher_type_id',$this->getCompanyWiseDataTypes(null)->pluck('id')->toArray());
        return $data;
    }
    protected function getFolderSize($dir)
    {
        $size = 0;
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $size += is_file($path) ? filesize($path) : $this->getFolderSize($path);
        }
        return $size;
    }

    protected function getStoragePackages()
    {
        return Data_archive_storage_package::withCount('useesCompany')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'useesCompany:id,company_name'
            ]);
    }
}
