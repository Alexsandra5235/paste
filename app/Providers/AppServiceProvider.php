<?php

namespace App\Providers;

use App\Models\Paste;
use App\Models\User;
use App\Repository\InfoPasteRepository;
use App\Repository\PasteApiRepository;
use App\Repository\PasteRepository;
use App\Repository\ReportRepository;
use App\Service\InfoPasteService;
use App\Service\PasteApiService;
use App\Service\PasteService;
use App\Service\ReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PasteApiRepository::class, function ($app) {
            return new PasteApiRepository();
        });

        $this->app->singleton(ReportRepository::class, function ($app) {
            return new ReportRepository();
        });

        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService(
                $app->make(ReportRepository::class),
            );
        });

        $this->app->singleton(InfoPasteRepository::class, function ($app) {
            return new InfoPasteRepository();
        });

        $this->app->singleton(InfoPasteService::class, function ($app) {
            return new InfoPasteService(
                $app->make(InfoPasteRepository::class)
            );
        });

        $this->app->singleton(PasteApiService::class, function ($app) {
            return new PasteApiService(
                $app->make(PasteApiRepository::class),
                $app->make(ReportService::class),
                $app->make(InfoPasteService::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
        });
        View::composer('*', function ($view) {
            $pasteService = app(PasteService::class);
            $pasteService->deleteExpired();
            $latestPastes = app(PasteRepository::class)->findAll();
            $view->with(['latestPastes' => $latestPastes,'nowDate' => now()]);
        });
        View::composer('layouts.myPaste', function ($view) {
            if(Auth::user()->api_key == null){
                $topPastes = [];
            } else{
                $latestPastes = app(PasteApiService::class)->getPasteByUser(Auth::user()->api_key);
                $topPastes = array_slice($latestPastes['pastes'], 0, 10);
            }

            $view->with(['latestUserPastes' => $topPastes, 'nowDate' => now()]);
        });
    }
}
