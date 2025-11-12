<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::where('is_published', true)
            ->with(['user', 'category'])
            ->latest()
            ->limit(5)
            ->get();

        return view('home', compact('latestNews'));
    }
}

