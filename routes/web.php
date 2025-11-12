<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UCP\UCPController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::post('/news/{news}/comment', [NewsController::class, 'comment'])->middleware('auth')->name('news.comment');

// Forum
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/category/{category}', [ForumController::class, 'showCategory'])->name('forum.category');
Route::get('/forum/thread/{thread}', [ForumController::class, 'showThread'])->name('forum.thread');
Route::post('/forum/category/{category}/thread', [ForumController::class, 'createThread'])->middleware('auth')->name('forum.thread.create');
Route::post('/forum/thread/{thread}/post', [ForumController::class, 'createPost'])->middleware('auth')->name('forum.post.create');

// Support
Route::get('/support', [SupportController::class, 'index'])->middleware('auth')->name('support.index');
Route::get('/support/create', [SupportController::class, 'create'])->middleware('auth')->name('support.create');
Route::post('/support', [SupportController::class, 'store'])->middleware('auth')->name('support.store');
Route::get('/support/{ticket}', [SupportController::class, 'show'])->middleware('auth')->name('support.show');
Route::post('/support/{ticket}/reply', [SupportController::class, 'reply'])->middleware('auth')->name('support.reply');

// Chat
Route::get('/chat', [ChatController::class, 'index'])->middleware('auth')->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware('auth')->name('chat.send');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->middleware('auth')->name('chat.messages');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/product/{product}', [ShopController::class, 'showProduct'])->name('shop.product');
Route::post('/shop/product/{product}/cart', [ShopController::class, 'addToCart'])->middleware('auth')->name('shop.cart.add');
Route::get('/shop/cart', [ShopController::class, 'cart'])->middleware('auth')->name('shop.cart');
Route::post('/shop/checkout', [ShopController::class, 'checkout'])->middleware('auth')->name('shop.checkout');
Route::get('/shop/orders', [ShopController::class, 'orders'])->middleware('auth')->name('shop.orders');

// UCP
Route::prefix('ucp')->middleware('auth')->group(function () {
    Route::get('/', [UCPController::class, 'dashboard'])->name('ucp.dashboard');
});

// Admin
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::resource('news', AdminNewsController::class)->names([
        'index' => 'admin.news.index',
        'create' => 'admin.news.create',
        'store' => 'admin.news.store',
        'show' => 'admin.news.show',
        'edit' => 'admin.news.edit',
        'update' => 'admin.news.update',
        'destroy' => 'admin.news.destroy',
    ]);
});

