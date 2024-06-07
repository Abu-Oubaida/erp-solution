<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    //
    public function index()
    {
        return view('back-end/control-panel/index');
    }
}
