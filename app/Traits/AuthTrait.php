<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthTrait
{
    protected $user;

    public function setUser()
    {
        $this->user = Auth::user();
    }

//    public function getUser()
//    {
//        return $this->user;
//    }
}
