@if (Auth::user()->is_banned == 1)
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        Ваш аккаунт заблокирован! Переход на другие страницы невозможен.
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="р1 text-center mb-3">Все пасты</h1>
                        @if(session('success'))
                            <div class="alert alert-success">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        <div class="list-group">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                                @isset($currentItems)
                                    @if(!empty($currentItems) && count($currentItems) > 0)
                                        @foreach($currentItems as $paste)
                                            <div class="col">
                                                <div class="card shadow-sm" style="height: 100%">
                                                    <div class="card-header">
                                                        Title: {{ $paste['paste_title'] }}
                                                    </div>
                                                    <div class="card-body" style="width: 100%">
                                                        <p class="card-text">Key: {{ $paste['paste_key'] }}</p>
                                                        <p class="card-text">Date: {{ date('Y-m-d H:i:s', $paste['paste_date']) }}</p>
                                                        <p class="card-text">Size: {{ $paste['paste_size'] }} bytes</p>
                                                        <p class="card-text">Expiration Date: {{ $paste['paste_expire_date'] == 0 ? 'Never' : date('Y-m-d H:i:s', $paste['paste_expire_date']) }}</p>
                                                        <p class="card-text">Privacy:
                                                            @if($paste['paste_private'] == '0') Public @endif
                                                            @if($paste['paste_private'] == '2') Private @endif
                                                            @if($paste['paste_private'] == '1') Unlisted @endif
                                                        </p>
                                                        <p class="card-text">Format: {{ $paste['paste_format_long'] }} ({{ $paste['paste_format_short'] }})</p>
                                                        <p class="card-text">Hits: {{ $paste['paste_hits'] }}</p>
                                                        <div class="container" style="width: 100%">
                                                            <a href="{{ $paste['paste_url'] }}" class="btn btn-primary">View Paste</a>
                                                            @php
                                                                $userHasPaste = false;
                                                            @endphp
                                                            @isset($listUrl)
                                                                @foreach($listUrl as $title => $url)
                                                                    @if($paste['paste_url'] == $url)
                                                                        @php
                                                                            $userHasPaste = true;
                                                                        @endphp
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @endisset
                                                            @if(!$userHasPaste)
                                                                <a href="{{ route('report.index',['url' => $paste['paste_key']]) }}" class="btn btn-danger">Ban paste</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endisset
                            </div>
                        </div>
                        <div>
                            @isset($currentPage, $totalPages)
                                @if($totalPages > 1)
                                    <nav aria-label="Page navigation example" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            @if($currentPage > 1)
                                                <li class="page-item">
                                                    <a class="page-link" href="?page={{ $currentPage - 1 }}">Назад</a>
                                                </li>
                                            @endif

                                            @for ($i = 1; $i <= $totalPages; $i++)
                                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                                </li>
                                            @endfor

                                            @if($currentPage < $totalPages)
                                                <li class="page-item">
                                                    <a class="page-link" href="?page={{ $currentPage + 1 }}">Вперёд</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                @endif
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif

