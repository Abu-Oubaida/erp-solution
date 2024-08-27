<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBranchChangeHistory extends Model
{
    use HasFactory;
    protected $table = 'branch_transfer_histories';
    protected $fillable = ['company_id','transfer_user_id','new_branch_id','from_branch_id','transfer_by'];
    public function getTransferUser()
    {
        return $this->belongsTo(User::class,'transfer_user_id');
    }
    public function getNewBranch()
    {
        return $this->belongsTo(branch::class,'new_branch_id');
    }
    public function getOldBranch()
    {
        return $this->belongsTo(branch::class,'from_branch_id');
    }
    public function transferedBy()
    {
        return $this->belongsTo(User::class,'transfer_by');
    }
}
