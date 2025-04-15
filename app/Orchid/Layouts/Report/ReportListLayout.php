<?php

namespace App\Orchid\Layouts\Report;

use App\Models\Report;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ReportListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'reports';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
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
                        ->icon('globe')
                        ->href($report->paste_url)
                        ->target('_blank'),
                    Button::make(__('Удалить пасту'))
                        ->icon('bs.trash3')
                        ->confirm(__('После удаления пасты все её ресурсы и данные будут безвозвратно удалены. '))
                        ->method('remove', [
                            'paste_url' => $report->paste_url,
                        ]),
                ]))

        ];
    }
}
