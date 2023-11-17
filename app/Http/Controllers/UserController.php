<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function findAll(){
         
        $user = User::find(1); 
        return response()->json($user);   
    }
}
