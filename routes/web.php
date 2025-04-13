<?php

use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\PasteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UnbanUser;
use App\Orchid\Screens\User\UserEditScreen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(UnbanUser::class)->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/login/yandex', [AuthSessionController::class, 'yandex'])->name('yandex');
    Route::get('/login/yandex/redirect', [AuthSessionController::class, 'yandexRedirect'])->name('yandexRedirect');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::post('/paste', [PasteController::class, 'store'])->name('paste.store');
    Route::get('/paste', [PasteController::class, 'index'])->name('paste.index');

    Route::post('/paste/user',[UserController::class, 'store'])->name('paste.user.store');
    Route::get('/paste/user',[UserController::class, 'login'])->name('paste.user.index');

});


require __DIR__.'/auth.php';
