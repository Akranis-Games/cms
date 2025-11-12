<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UCPController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('ucp.dashboard');
    }
}

