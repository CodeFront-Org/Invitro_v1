<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profileUpdate(){
        $label="My Profile";
        return view('app.profile',compact('label'));
    }
}