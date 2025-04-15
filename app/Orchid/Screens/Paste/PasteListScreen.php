<?php

namespace App\Orchid\Screens\Paste;

use App\Orchid\Layouts\Paste\PasteListLayout;
use App\Service\PasteApiService;
use Illuminate\Http\Client\ConnectionException;
use Orchid\Screen\Screen;

class PasteListScreen extends Screen
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
     * @throws ConnectionException
     */
    public function query(): iterable
    {
        $pastes = $this->pasteApiService->findAllRenderPastes();
        return [
            'pastes' => $pastes
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список всех паст';
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
            PasteListLayout::class
        ];
    }
}
