<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_published', true)
            ->with(['user', 'category'])
            ->latest()
            ->paginate(10);

        $categories = NewsCategory::all();

        return view('news.index', compact('news', 'categories'));
    }

    public function show(News $news)
    {
        if (!$news->is_published) {
            abort(404);
        }

        $news->increment('views');
        $comments = $news->comments()->where('is_approved', true)->with('user')->latest()->get();

        return view('news.show', compact('news', 'comments'));
    }

    public function comment(Request $request, News $news)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        NewsComment::create([
            'news_id' => $news->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_approved' => auth()->user()->isModerator(),
        ]);

        return back()->with('success', 'Kommentar erfolgreich hinzugefügt');
    }
}

