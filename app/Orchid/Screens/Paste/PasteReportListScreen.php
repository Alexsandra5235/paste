<?php

namespace App\Orchid\Screens\Paste;

use App\Models\Paste;
use App\Models\Report;
use App\Orchid\Layouts\Paste\PasteReportListLayouts;
use App\Service\PasteApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use function Symfony\Component\Translation\t;

/**
 *
 */
class PasteReportListScreen extends Screen
{
    protected PasteApiService $pasteApiService;
    public function __construct(PasteApiService $pasteApiService)
    {
        $this->pasteApiService = $pasteApiService;
    }
    /**
     * @var string
     */
    public $paste_url;
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
            'paste_url' => $paste_url,
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
    public function description(): ?string
    {
        return 'Ссылка на пасту ' . $this->paste_url;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Удалить пасту'))
                ->icon('bs.plus-circle')
                ->confirm(__('После удаления пасты все её ресурсы и данные будут безвозвратно удалены. '))
                ->method('remove',
                    ['paste_url'=>$this->paste_url]),
            Link::make('Открыть пасту')
                ->icon('bs-circle')
                ->href($this->paste_url)
                ->target('_blank'),
        ];
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

    /**
     * @throws ConnectionException
     */
    public function remove(string $paste_url): RedirectResponse
    {
        $request = $this->pasteApiService->delete($paste_url);

        if(array_key_exists('status', $request)){

            if($request['status'] == 'success'){
                Toast::info(__('Pastes was removed'));
            } else {
                Toast::info(__('Pastes was not removed. Error: ' . $request['message']));
            }
        } else {

            Toast::info(__('Непредвиденная ошибка'));
        }

        return redirect()->route('platform.pastes');

    }
}
