<x-app-layout>
    <div class="container">
        <strong class="justify-content-center my-5">Your Pastes</strong>

        @if($error != '')
            <div class="card mb">
                <div class="card-header">
                    Уведомление
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <p>{{ $error }}</p>
                    </blockquote>
                </div>
            </div>
        @endif

        @if(count($pastes) > 0)
            <div class="list-group">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @foreach($pastes as $paste)
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Title: {{ $paste['paste_title'] }}
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Key: {{ $paste['paste_key'] }}</p>
                                    <p class="card-text">Date: {{ date('Y-m-d H:i:s', $paste['paste_date']) }}</p>
                                    <p class="card-text">Size: {{ $paste['paste_size'] }} bytes</p>
                                    <p class="card-text">Expiration Date: {{ $paste['paste_expire_date'] == 0 ? 'Never' : date('Y-m-d H:i:s', $paste['paste_expire_date']) }}</p>
                                    <p class="card-text">Privacy: {{ $paste['paste_private'] == 1 ? 'Private' : 'Public' }}</p>
                                    <p class="card-text">Format: {{ $paste['paste_format_long'] }} ({{ $paste['paste_format_short'] }})</p>
                                    <p class="card-text">Hits: {{ $paste['paste_hits'] }}</p>
                                    <a href="{{ $paste['paste_url'] }}" class="btn btn-primary">View Paste</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
