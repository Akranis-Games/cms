<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::with(['threads' => function($query) {
            $query->latest()->limit(5);
        }])->orderBy('order')->get();

        return view('forum.index', compact('categories'));
    }

    public function showCategory(ForumCategory $category)
    {
        $threads = $category->threads()->with(['user', 'latestPost.user'])->latest()->paginate(20);
        return view('forum.category', compact('category', 'threads'));
    }

    public function showThread(ForumThread $thread)
    {
        $thread->increment('views');
        $posts = $thread->posts()->with('user')->paginate(20);
        return view('forum.thread', compact('thread', 'posts'));
    }

    public function createThread(Request $request, ForumCategory $category)
    {
        $this->middleware('auth');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread = ForumThread::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'category_id' => $category->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('forum.thread', $thread);
    }

    public function createPost(Request $request, ForumThread $thread)
    {
        $this->middleware('auth');

        if ($thread->is_locked && !auth()->user()->isModerator()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Post erfolgreich erstellt');
    }
}

