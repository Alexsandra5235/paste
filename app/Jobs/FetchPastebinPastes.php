<?php

namespace App\Jobs;

use App\Models\User;
use App\Service\PasteApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class FetchPastebinPastes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $cacheKey;

    public function __construct(string $cacheKey = 'pastes')
    {
        $this->cacheKey = $cacheKey;
    }

    public function handle(PasteApiService $pasteApiService): void
    {
        try {
            $pastes = $pasteApiService->findAll(0);
            Cache::put($this->cacheKey, $pastes, now()->addMinutes(5));
        } catch (\Throwable $e) {
            Log::error("Ошибка при получении паст: " . $e->getMessage());
        }
    }
}
