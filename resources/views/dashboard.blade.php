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
                        <div class="list-group">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                @foreach($response['pastes']['paste'] as $paste)

                                    <div class="col">
                                        <div class="card shadow-sm" style="height: 100%">
                                            <div class="card-header">
                                                Title: {{ $paste['paste_title'] }}
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Key: {{ $paste['paste_key'] }}</p>
                                                <p class="card-text">Date: {{ date('Y-m-d H:i:s', $paste['paste_date']) }}</p>
                                                <p class="card-text">Size: {{ $paste['paste_size'] }} bytes</p>
                                                <p class="card-text">Expiration Date: {{ $paste['paste_expire_date'] == 0 ? 'Never' : date('Y-m-d H:i:s', $paste['paste_expire_date']) }}</p>
                                                <p class="card-text">Privacy: @if($paste['paste_private'] == 0) Public @endif {{ $paste['paste_private'] == 2 ? 'Private' : 'Unlisted' }}</p>
                                                <p class="card-text">Format: {{ $paste['paste_format_long'] }} ({{ $paste['paste_format_short'] }})</p>
                                                <p class="card-text">Hits: {{ $paste['paste_hits'] }}</p>
                                                <a href="{{ $paste['paste_url'] }}" class="btn btn-primary">View Paste</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif

