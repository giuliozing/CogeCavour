<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProtectedData extends Controller
{
    public function index(){
        return User::all();
    }
}
?>
