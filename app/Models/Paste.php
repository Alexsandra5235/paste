<?php

namespace App\Models;

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
}
