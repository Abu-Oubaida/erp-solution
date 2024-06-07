<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanySetup extends Controller
{
    //
    public function index()
    {
        return view('back-end/programmer/company-setup');
    }
}
