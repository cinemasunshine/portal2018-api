<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function __invoke()
    {
        return view('home');
    }
}
