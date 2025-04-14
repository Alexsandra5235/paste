<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfoPastes extends Model
{
    protected $table = 'info_pastes';
    protected $fillable = ['user_id', 'paste_url'];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
