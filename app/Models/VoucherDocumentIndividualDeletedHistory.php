<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentIndividualDeletedHistory extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_info_id','document','filepath','created_by','updated_by','deleted_by','restored_by','restored_status'];
    public function accountVoucherInfo()
    {
        return $this->belongsTo(Account_voucher::class,'voucher_info_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class,'deleted_by');
    }
    public function restoredBy()
    {
        return $this->belongsTo(User::class,'restored_by');
    }
}
