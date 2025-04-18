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
        function getPrivacyLabel(value) {
            if (value === '0') return 'Public';
            if (value === '1') return 'Unlisted';
            if (value === '2') return 'Private';
            return 'Unknown';
        }

        function formatTimestamp(timestamp) {
            if (!timestamp || timestamp == 0) return 'Never';
            const date = new Date(timestamp * 1000);
            return date.toLocaleString();
        }

        function renderPastes(pastes, listUrl) {
            const container = document.getElementById('pastes-container');
            if (!container) {
                console.warn('Контейнер для паст не найден. Возможно, вы не на главной странице.');
                return;
            }
            container.innerHTML = '';

            pastes.forEach(paste => {
                const col = document.createElement('div');
                col.className = 'col';

                const isUserPaste = Object.values(listUrl).includes(paste.paste_url);

                col.innerHTML = `
                    <div class="card shadow-sm" style="height: 100%">
                        <div class="paste" id="paste-${paste.id}">
                            <div class="card-header">
                                Title: ${paste['paste_title']}
                            </div>
                            <div class="card-body" style="width: 100%">
                                <p class="card-text">Key: ${paste.paste_key}</p>
                                <p class="card-text">Date: ${formatTimestamp(paste.paste_date)}</p>
                                <p class="card-text">Size: ${paste.paste_size} bytes</p>
                                <p class="card-text">Expiration Date: ${formatTimestamp(paste.paste_expire_date)}</p>
                                <p class="card-text">Privacy: ${getPrivacyLabel(paste.paste_private)}</p>
                                <p class="card-text">Format: ${paste.paste_format_long} (${paste.paste_format_short})</p>
                                <p class="card-text">Hits: ${paste.paste_hits}</p>
                                <div class="container" style="width: 100%">
                                    <a href="${paste.paste_url}" class="btn btn-primary">View Paste</a>
                                    ${!isUserPaste ? `<a href="/report/store/${paste.paste_key}" class="btn btn-danger">Ban paste</a>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        async function fetchPastes() {
            try {
                const res = await fetch('/api/pastes');
                const result = await res.json();
                let listUrl = await fetch('/url/user')
                const listUrlResult = await listUrl.json();

                if (!listUrlResult.success) {
                    listUrl = []
                }

                if (result.success) {
                    const pastes = result.data;
                    console.log('Полученные пасты:', pastes);

                    renderPastes(pastes, listUrlResult.data);
                } else {
                    console.error('Ошибка при загрузке паст:', result.message);
                }
            } catch (err) {
                console.error('Ошибка загрузки паст:', err);
            }
        }

        setInterval(fetchPastes, 5000);

    </script>
</html>
