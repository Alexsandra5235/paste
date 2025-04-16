<?php

namespace App\Providers;

use App\Repository\PasteApiRepository;
use App\Repository\ReportRepository;
use App\Service\PasteApiService;
use App\Service\ReportService;
use Carbon\Carbon;
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

        $this->app->singleton(PasteApiService::class, function ($app) {
            return new PasteApiService(
                $app->make(PasteApiRepository::class),
                $app->make(ReportService::class),
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

        View::composer('layouts.paste', function ($view) {
            $latestPastes = app(PasteApiRepository::class)->findLastPastes();
            $view->with(['latestPastes' => $latestPastes,'now' => Carbon::now()]);
        });

        View::composer('layouts.myPaste', function ($view) {
            $latestPastes = app(PasteApiService::class)->getPastesByUser(null);
            if(array_key_exists('pastes', $latestPastes) && $latestPastes['pastes'] != []){
                $splicePaste = array_splice($latestPastes['pastes']['paste'], 0, 10);
                $latestPastes['pastes']['paste'] = $splicePaste;
            }
            $view->with(['latestUserPastes' => $latestPastes, 'now' => Carbon::now()]);
        });
    }
}
