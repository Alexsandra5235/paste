<?php

namespace App\Orchid\Screens;

use App\Models\Report;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ReportScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $reports = Report::query()
            ->latest()
            ->paginate(10);
        return [
            'reports' => $reports,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список жалоб';
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
            Layout::table('reports', [
                TD::make('id', 'ID'),
                TD::make('paste_url', 'Название')->sort(),
                TD::make('reason', 'Описание'),
                TD::make('created_at', 'Создано')->sort(),
                TD::make('updated_at', 'Обновлено')->sort(),
                TD::make('actions', 'Действия')->render(function (Report $complaint) {
                    return Link::make('Просмотреть')
                        ->route('platform.reports', $complaint->id);
                }),
            ]),
        ];
    }
}
