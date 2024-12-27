<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class GlobalController extends Controller
{
    public function __construct()
    {
        $this->middleware('server');
    }
}
