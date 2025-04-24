<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class Account_voucher extends Model
{
    protected $table = 'account_voucher_infos';
    use HasFactory;
    protected $fillable = ['id','company_id', 'voucher_type_id', 'voucher_number', 'voucher_date', 'file_count','remarks','project_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function VoucherType()
    {
        return $this->belongsTo(VoucherType::class,'voucher_type_id');
    }

    public function VoucherDocument()
    {
        return $this->hasMany(VoucherDocument::class,'voucher_info_id');
    }

    public function voucherDocuments()
    {
        // $d = $this->belongsToMany(VoucherDocument::class,'archive_info_link_documents','voucher_info_id','document_id');
        // Log::info(json_encode($d,JSON_PRETTY_PRINT));
        return $this->belongsToMany(VoucherDocument::class,'archive_info_link_documents','voucher_info_id','document_id');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id');
    }
    public function createdBY()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBY()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function project(){
        return $this->belongsTo(Branch::class,'project_id','id');
    }
}
