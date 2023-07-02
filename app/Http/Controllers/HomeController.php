<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function home() {

        if (session('user_name') !== null) {
            return redirect('/logged');
        }

        return view('home');
    }

    public function logged_home() {

        if (session('user_name') === null) {
            return redirect('/');
        }

        return view('logged_home');
    }
}
