<?php

namespace App\Repository;

use App\Interfaces\ReportRepositoryInterface;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request): Report
    {
        return Report::query()->create([
            'user_id' => Auth::id(),
            'paste_url' => $request->get('paste_url'),
            'reason' => $request->get('reason'),
        ]);
    }
}
