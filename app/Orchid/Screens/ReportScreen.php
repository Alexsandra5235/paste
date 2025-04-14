<?php

namespace App\Orchid\Screens;

use App\Models\Report;
use App\Models\User;
use App\Repository\PasteApiRepository;
use App\Repository\ReportRepository;
use App\Service\PasteApiService;
use App\Service\ReportService;
use Illuminate\Http\Client\ConnectionException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReportScreen extends Screen
{
    protected PasteApiService $pasteApiService;
    public function __construct(PasteApiService $pasteApiService)
    {
        $this->pasteApiService = $pasteApiService;
    }
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
                TD::make('complaints_count', 'Количество жалоб на данную пасту')
                ->render(fn (Report $report) => $report->countReport($report->paste_url)),
                TD::make('actions', 'Действия')->render(fn (Report $report) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Открыть пасту'))
                            ->href($report->paste_url)
                            ->target('_blank'),
                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm(__('После удаления пасты все её ресурсы и данные будут безвозвратно удалены. '))
                            ->method('remove', [
                                'paste_url' => $report->paste_url,
                            ]),
                    ]))
            ]),
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function remove(string $paste_url): void
    {
        $request = $this->pasteApiService->delete($paste_url);

        if(!array_key_exists('error', $request)){
            Toast::info(__('Pastes was removed'));
        }

    }
}
