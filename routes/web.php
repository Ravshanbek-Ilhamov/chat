<?php

use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat',[MessagesController::class, 'chat_page'])->name('chat.index');
    Route::get('/messageTo/{id}',[MessagesController::class, 'message_page'])->name('message.index');
    Route::post('/message-store',[MessagesController::class, 'message_store'])->name('message.store');
});

require __DIR__.'/auth.php';
