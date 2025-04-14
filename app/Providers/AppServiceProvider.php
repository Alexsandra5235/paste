<?php

namespace App\Providers;

use App\Models\Paste;
use App\Models\User;
use App\Repository\PasteApiRepository;
use App\Repository\PasteRepository;
use App\Service\PasteApiService;
use App\Service\PasteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected PasteRepository $pasteRepository;
    protected PasteApiRepository $pasteApiRepository;
    protected PasteService $pasteService;
    protected PasteApiService $pasteApiService;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->pasteRepository = new PasteRepository();
        $this->pasteApiRepository = new PasteApiRepository();
        $this->pasteService = new PasteService($this->pasteRepository);
        $this->pasteApiService = new PasteApiService($this->pasteApiRepository);

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
        });
        View::composer('*', function ($view) {
            $this->pasteService->deleteExpired();
            $latestPastes = $this->pasteRepository->findAll();
            $view->with(['latestPastes' => $latestPastes,'nowDate' => now()]);
        });
        View::composer('layouts.myPaste', function ($view) {
            $latestPastes = $this->pasteApiService->getPasteByUser(Auth::user()->api_key);
            $topPastes = array_slice($latestPastes['pastes'], 0, 10);
            $view->with(['latestUserPastes' => $topPastes, 'nowDate' => now()]);
        });
    }
}
