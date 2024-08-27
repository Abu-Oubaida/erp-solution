<?php
namespace App\Traits;
use App\Models\branch;
use App\Models\BranchType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait BranchParent
{
    protected $user;

    public function setUser()
    {
        $this->user = Auth::user();
    }

    public function getUser()
    {
        return $this->user;
    }
    protected function getBranch()
    {
        if($this->user->isSystemSuperAdmin())
        {
            return branch::with(['branchType','createdBy','updatedBy','company']);
        }
        return branch::with(['branchType','createdBy','updatedBy','company'])->where('company_id',$this->user->company_id);
    }
    protected function getBranchType()
    {
        if ($this->user->isSystemSuperAdmin())
        {
            return BranchType::with(['getBranch','createdBy','updatedBy','company']);
        }
        return BranchType::with(['branchType','createdBy','updatedBy','company'])->where('company_id',$this->user->company_id);
    }
}
