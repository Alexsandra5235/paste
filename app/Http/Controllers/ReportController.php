<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Service\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 *
 */
class ReportController extends Controller
{

    /**
     * Создание жалобы на пасту.
     * @param Request $request
     * @return RedirectResponse
     * Возвращает обратно на страницу создания жалобы
     * с результатом ее добавления.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paste_url' => 'required|url',
            'reason' => 'required',
        ]);

        app(ReportService::class)->create($request);
        return redirect()->route('dashboard')->with('success', 'Жалоба успешно отправлена');
    }

    /**
     * Отображает страницу добавления жалобы на пасту.
     * @param string $url
     * Ссылка на пасту, на которую будет написана жалоба.
     * @return View
     * Возвращает страницу создания жалобы.
     */
    public function index(string $url) : View
    {
        return view('paste.report',['url' => $url]);
    }
}
