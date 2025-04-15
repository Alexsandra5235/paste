<?php

namespace App\Orchid\Screens\Paste;

use App\Orchid\Layouts\Paste\PasteListLayout;
use App\Service\PasteApiService;
use Illuminate\Http\Client\ConnectionException;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

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

    /**
     * @throws ConnectionException
     */
    public function remove(string $paste_url): void
    {
        $request = $this->pasteApiService->delete($paste_url);

        if(array_key_exists('status', $request)){

            if($request['status'] == 'success'){
                Toast::info(__('Pastes was removed'));
            } else {
                Toast::info(__('Pastes was not removed. Error: ' . $request['message']));
            }
        } else {

            Toast::info(__('Pastes'));
        }


    }
}
