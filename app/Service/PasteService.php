<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Models\Paste;
use App\Repository\PasteRepository;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PasteService
{
    protected PasteRepository $pasteRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PasteRepository $pasteRepository)
    {
        $this->pasteRepository = $pasteRepository;
    }

    /**
     * @throws ConnectionException
     */
    public function createPaste(PasteDTO $pasteDTO, Request $request): string
    {
        return $this->pasteRepository->create($pasteDTO);
    }
    public function createPasteDB(PasteDTO $pasteDTO, string $url) : Paste | null
    {
//        $this->deleteExpired();
        if($pasteDTO->pastePrivate != 0) return null;

        if(Paste::query()->count() >= 10){
            Paste::query()->orderBy('created_at')->first()->delete();
        }
        return $this->pasteRepository->createDB($url, $pasteDTO);
    }
    public function deleteExpired(): void
    {
        $pastes = Paste::all();

        foreach ($pastes as $paste) {
            if ($this->isExpired($paste->created_at, $paste->paste_expire_date)) {
                $paste->delete();
            }
        }
    }
    private function isExpired($createdAt, $expireDateFormat): bool
    {
        $expireAt = clone $createdAt;

        switch ($expireDateFormat) {
            case 'N':
                return false;
            case '10M':
                $expireAt->addMinutes(10);
                break;
            case '1H':
                $expireAt->addHour();
                break;
            case '1D':
                $expireAt->addDay();
                break;
            case '1W':
                $expireAt->addWeek();
                break;
            case '2W':
                $expireAt->addWeeks(2);
                break;
            case '1M':
                $expireAt->addMonth();
                break;
            case '6M':
                $expireAt->addMonths(6);
                break;
            case '1Y':
                $expireAt->addYear();
                break;
        }

        return Carbon::now()->greaterThan($expireAt);
    }

}
