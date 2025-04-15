<?php

namespace App\Repository;

use App\Interfaces\ReportRepositoryInterface;
use App\Models\Paste;
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

    public function deleteByUrl(string $paste_url): void
    {
        Report::query()->where('paste_url', $paste_url)->delete();
    }


    /**
     * @return array
     */
    public function getReportByUser(array $pastes, array $countedUrls): array
    {
        $result = [];

        foreach ($pastes['pastes']['paste'] as $paste) {
            foreach ($countedUrls as $url => $count) {
                if (str_contains($paste['paste_url'], $url) !== false) {
                    $result[$paste['paste_title']] = $count;
                }
            }
        }
        return $result;
    }
}
