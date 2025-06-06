<div class="my-3 p-3 bg-body rounded-3" style="overflow-y: auto; max-height: 50vh">
    <h6 class="border-bottom pb-2 mb-0"><ya-tr-span data-index="19-0" data-translated="true" data-source-lang="en" data-target-lang="ru" data-value="Recent updates" data-translation="Последние обновления" data-ch="0" data-type="trSpan" style="visibility: inherit !important;">Последние public пасты</ya-tr-span></h6>
    @isset($latestPastes)
        @foreach($latestPastes as $paste)
            <a href="{{ $paste['paste_url'] }}">
                <div class="d-flex text-body-secondary pt-3">
                    <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"></rect><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                    <p class="pb-3 mb-0 small lh-sm border-bottom"  style="width: 100vw">
                        <strong class="d-block text-gray-dark">
                            {{ $paste['paste_title'] }}
                        </strong>
                        <ya-tr-span data-index="20-0" data-translated="true" data-source-lang="en" data-target-lang="ru" style="visibility: inherit !important;"> {{ Carbon\Carbon::createFromTimestamp($paste['paste_date'])->diffForHumans() }} | </ya-tr-span>
                        <ya-tr-span data-index="20-1" data-translated="true" data-source-lang="en" data-target-lang="ru" style="visibility: inherit !important;"> {{ $paste['paste_format_long'] }} ({{ $paste['paste_format_short'] }}) |</ya-tr-span>
                        <ya-tr-span data-index="20-2" data-translated="true" data-source-lang="en" data-target-lang="ru" style="visibility: inherit !important;"> Осталось: {{ \Carbon\Carbon::createFromTimestamp($paste['paste_expire_date'])->diff($now)->format('%d дней, %h часов, %i минут') }} </ya-tr-span>
                    </p>
                </div>
            </a>
        @endforeach
    @endisset

    <small class="d-block text-end mt-3">
        <a href="#"><ya-tr-span data-index="23-0" data-translated="true" data-source-lang="en" data-target-lang="ru" data-value="All updates" data-translation="Все обновления" data-ch="0" data-type="trSpan" style="visibility: inherit !important;">Все обновления</ya-tr-span></a>
    </small>
</div>
