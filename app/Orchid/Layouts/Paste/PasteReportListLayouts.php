<?php

namespace App\Orchid\Layouts\Paste;

use Dflydev\DotAccessData\Data;
use Dflydev\DotAccessData\DataInterface;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PasteReportListLayouts extends Table
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
     * @throws \ReflectionException
     */
    protected function columns(): iterable
    {
        return [
            TD::make('reason', 'Текст жалобы'),
            TD::make('created_at', 'Дата создания жалобы')
                ->usingComponent(DateTimeSplit::class)
        ];
    }
}
