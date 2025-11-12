<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\SupportTicket;
use App\Models\ShopOrder;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'news' => News::count(),
            'tickets' => SupportTicket::where('status', '!=', 'closed')->count(),
            'orders' => ShopOrder::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

