<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paste extends Model
{
    protected $fillable = [
        'paste_name',
        'paste_format',
        'paste_private',
        'paste_code',
        'paste_expire_date',
        'user_id',
        'url',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingTime(): string
    {
        if ($this->paste_expire_date === 'N') {
            return 'Без ограничения';
        }

        $now = Carbon::now();

        $createdAt = $this->created_at;

        $expireTime = clone $createdAt;

        switch ($this->paste_expire_date) {
            case '10M':
                $expireTime->addMinutes(10);
                break;
            case '1H':
                $expireTime->addHours(1);
                break;
            case '1D':
                $expireTime->addDays(1);
                break;
            case '1W':
                $expireTime->addWeeks(1);
                break;
            case '2W':
                $expireTime->addWeeks(2);
                break;
            case '1M':
                $expireTime->addMonths(1);
                break;
            case '6M':
                $expireTime->addMonths(6);
                break;
            case '1Y':
                $expireTime->addYears(1);
                break;
        }

        $remainingTime = $expireTime->diff($now);

        $parts = [];
        if ($remainingTime->d > 0) {
            $parts[] = "{$remainingTime->d} дн.";
        }
        if ($remainingTime->h > 0) {
            $parts[] = "{$remainingTime->h} ч.";
        }
        if ($remainingTime->i > 0) {
            $parts[] = "{$remainingTime->i} мин.";
        }

        return implode(' ', $parts);

    }
}
