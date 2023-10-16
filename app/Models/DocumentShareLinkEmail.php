<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentShareLinkEmail extends Model
{
    use HasFactory;
    protected $fillable = ['share_id','share_document_id','share_email','status','shared_by'];
}
