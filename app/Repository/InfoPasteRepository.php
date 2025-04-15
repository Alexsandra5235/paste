<?php

namespace App\Repository;

use App\Interfaces\InfoPasteRepositoryInterface;
use App\Models\InfoPastes;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class InfoPasteRepository implements InfoPasteRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(string $url): InfoPastes
    {
        return InfoPastes::query()->create([
            'paste_url' => $url,
            'user_id' => Auth::id(),
        ]);
    }

    public function getUserKeyByUrl(string $url) : string | null
    {
        try {
            $user_id = InfoPastes::query()->where('paste_url', $url)->firstOrFail()->user_id;
            return User::query()->where('id', $user_id)->firstOrFail()->api_key;
        } catch (ModelNotFoundException $e) {
            return null;
        }


    }

}
