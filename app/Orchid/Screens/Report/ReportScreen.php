<?php

namespace App\Orchid\Screens\Report;

use App\Models\Report;
use App\Orchid\Layouts\Report\ReportListLayout;
use App\Service\PasteApiService;
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
            ReportListLayout::class,
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
