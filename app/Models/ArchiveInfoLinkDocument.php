<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveInfoLinkDocument extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_info_id','company_id','document_id'];


}
