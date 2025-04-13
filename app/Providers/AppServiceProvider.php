<?php

namespace App\Providers;

use App\Models\Paste;
use App\Models\User;
use App\Repository\PasteRepository;
use App\Service\PasteService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public PasteRepository $pasteRepository;

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

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
        });
        View::composer('*', function ($view) {
            $latestPastes = $this->pasteRepository->findAll();
            $view->with(['latestPastes' => $latestPastes,'nowDate' => now()]);
        });
    }
}
