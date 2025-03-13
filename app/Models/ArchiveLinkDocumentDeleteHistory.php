<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveLinkDocumentDeleteHistory extends Model
{
    use HasFactory;

    protected $fillable = ['old_id','voucher_info_id','company_id','document_id','old_created_at','old_updated_at'];
}
