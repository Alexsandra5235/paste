<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class Report extends Model
{
    use AsSource, Filterable, Attachable;
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
    public function countReport(string $url) : int
    {
        return Report::query()->where('paste_url', $url)->count();
    }
}
