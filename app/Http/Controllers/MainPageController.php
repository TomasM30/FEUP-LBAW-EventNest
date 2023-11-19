<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;


class MainPageController extends Controller
{
    public function showForm() {
        return view('pages.test');
    }
}
