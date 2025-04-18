<?php

namespace App\Providers;

use App\Jobs\FetchPastebinPastes;
use App\Models\Paste;
use App\Repository\PasteApiRepository;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use App\Service\PasteApiService;
use App\Service\ReportService;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Yandex\YandexExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    protected array $listen = [
        SocialiteWasCalled::class => [
            YandexExtendSocialite::class.'@handle',
        ],
    ];
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

        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService(
                $app->make(ReportRepository::class),
            );
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepository::class),
            );
        });

        $this->app->singleton(PasteApiService::class, function ($app) {
            return new PasteApiService();
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
            $latestPastes = app(PasteApiService::class)->getCachePaste();
            $pastes = [];
            if ($latestPastes) {
                $pastes = array_slice($latestPastes, 0, 10);
            }
            $view->with(['latestPastes' => $pastes,'now' => Carbon::now()]);
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
