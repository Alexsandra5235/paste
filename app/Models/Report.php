<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'paste_url',
        'reason',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
