<?php

namespace App\Orchid\Screens\Paste;

use App\Models\Report;
use App\Orchid\Layouts\Paste\PasteReportListLayouts;
use Orchid\Screen\Screen;

class PasteReportListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(string $paste_key): iterable
    {
        $paste_url = env('BASE_URL_PASTEBIN') . $paste_key;
        $reports = Report::query()->where('paste_url', $paste_url)->get();
        return [
            'reports' => $reports,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Список жалоб на пасту';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            PasteReportListLayouts::class,
        ];
    }
}
