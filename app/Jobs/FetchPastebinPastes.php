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

    /**
     * @var string
     */
    protected string $cacheKey;

    /**
     * @param string $cacheKey
     */
    public function __construct(string $cacheKey = 'pastes')
    {
        $this->cacheKey = $cacheKey;
    }

    /**
     * Получает все публичные пасты и кэширует их в фоне.
     * @param PasteApiService $pasteApiService
     * @return void
     */
    public function handle(PasteApiService $pasteApiService): void
    {
        try {
            $pastes = $pasteApiService->findAll(0);

            if (!$pastes || !is_array($pastes)) {
                Log::warning("FetchPastebinPastes: Получен пустой результат.");
            }

            Cache::put($this->cacheKey, $pastes, now()->addMinutes(45));
            Log::info("Кеш паст успешно обновлён. Ключ: {$this->cacheKey}");
        } catch (\Throwable $e) {
            Log::error("Ошибка при получении паст: " . $e->getMessage());
        }
    }
}
