<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Report extends Model
{
    use AsSource;
    protected $fillable = [
        'user_id',
        'paste_url',
        'reason',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function relatedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'paste_url', 'paste_url');
    }
}
