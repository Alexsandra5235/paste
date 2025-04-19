<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            @include('layouts.nav')

            <div class="container">
                <div class="row">
                    <div class="col-md-3 pe-0" style="margin-top: 31px">
                        @include('layouts.paste')
                        @include('layouts.myPaste')
                    </div>
                    <div class="col-md-9">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>
        function updatePastesPeriodically() {
            fetch('{{ route('dashboard') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newPastes = doc.querySelector('#pastes-container');
                    const newPagination = doc.querySelector('#pagination');

                    document.querySelector('#pastes-container').innerHTML = newPastes.innerHTML;
                    document.querySelector('#pagination').innerHTML = newPagination.innerHTML;
                })
                .catch(err => {
                    console.error('Ошибка при получении паст:', err);
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.pathname === '/dashboard') {
                updatePastesPeriodically();

                setInterval(updatePastesPeriodically, 10000);
            }
        });
    </script>
</html>
