<?php

namespace App\Orchid\Layouts\Paste;

use App\Service\PasteApiService;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PasteListLayout extends Table
{
    protected PasteApiService $pasteApiService;
    public function __construct(PasteApiService $pasteApiService)
    {
        $this->pasteApiService = $pasteApiService;
    }
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pastes';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('paste_date', 'Дата создания')
                ->render(fn($paste) => date('d M Y H:i', $paste->paste_date)),
            TD::make('paste_title', 'Название'),
            TD::make('paste_size', 'Размер (байты)'),
            TD::make('paste_expire_date', 'Дата истечения')
                ->render(fn($paste) => $paste->paste_expire_date == 0 ? 'Никогда' : date('d M Y H:i', $paste->paste_expire_date)),
            TD::make('paste_private', 'Приватность')
                ->render(fn($paste) => $paste->paste_private == 0 ? 'Public' : ($paste->paste_private == 1 ? 'Unlisted' : 'Private')),
            TD::make('paste_format_long', 'Формат')
                ->render(fn($paste) => $paste->paste_format_long . "(" . $paste->paste_format_short . ")"),
            TD::make('paste_format_short', 'Формат короткий'),
            TD::make('paste_url', 'Ссылка на пасту')
                ->render(fn($paste) => Link::make($paste->paste_url)->href($paste->paste_url)->target('_blank')),
            TD::make('paste_hits', 'Количество просмотров'),

            TD::make('paste_format_long', 'Количество жалоб')
                ->render(fn($paste) => $this->pasteApiService->getCountReportByUrl($paste->paste_url)),

            TD::make('actions', 'Действия')->render(fn($paste) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make(__('Открыть пасту'))
                        ->icon('globe')
                        ->href($paste->paste_url)
                        ->target('_blank'),
                    Button::make(__('Удалить пасту'))
                        ->icon('bs.trash3')
                        ->confirm(__('После удаления пасты все её ресурсы и данные будут безвозвратно удалены. '))
                        ->method('remove', [
                            'paste_key' => $paste->paste_key,
                        ]),
                ])),
        ];
    }
}
