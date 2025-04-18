<?php

use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PasteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YandexController;
use App\Http\Middleware\UnbanUser;
use App\Jobs\FetchPastebinPastes;
use App\Orchid\Screens\User\UserEditScreen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(UnbanUser::class)->group(function () {
    Route::get('/', function () {
        FetchPastebinPastes::dispatch('pastes');
        return view('welcome');
    });

    Route::get('/dashboard', [MainController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/api/pastes', [PasteController::class, 'apiPastes'])->name('api.pastes');
    Route::get('/url/user', [PasteController::class, 'getUrlUser'])->name('url.user');

    Route::get('/login/yandex', [YandexController::class, 'redirectToYandex'])->name('login.yandex');
    Route::get('/login/yandex/redirect', [YandexController::class, 'catchCode'])->name('login.yandex.catch');
    Route::post('/login/yandex/authorize', [YandexController::class, 'handleCallback'])->name('login.yandex.authorize');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/paste', [PasteController::class, 'store'])->name('paste.store');
//        Route::post('/paste', [PasteController::class, 'test'])->name('paste.store');
        Route::get('/paste', [PasteController::class, 'index'])->name('paste.index');

        Route::post('/paste/user',[UserController::class, 'store'])->name('paste.user.store');
        Route::get('/paste/user',[UserController::class, 'login'])->name('paste.user.index');

        Route::get('/user/pastes',[PasteController::class, 'getPasteByUser'])->name('user.pastes');
//        Route::get('/user/pastes',[PasteController::class, 'userOrPaste'])->name('user.pastes');

        Route::get('/report/store/{url}',[ReportController::class, 'index'])->name('report.index');
        Route::post('/report/store',[ReportController::class, 'store'])->name('report.store');
    });



});


require __DIR__.'/auth.php';
