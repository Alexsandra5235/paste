<x-app-layout>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ __('Заполнение формы жалобы') }}
        </h2>
    </header>
    <form action="{{ route('report.store') }}" method="post">
        @csrf
        <div class="mb-2">
            <label for="paste_url" class="form-input">URL пасты</label>
            <input id="paste_url" name="paste_url" value="https://pastebin.com/{{ $url }}" type="text" class="mt-1 block w-full bg-dark"/>
        </div>
        <div class="mb-2">
            <label for="reason">Причина жалобы</label>
            <textarea id="reason" name="reason" class="mt-1 block w-full bg-dark"></textarea>
        </div>
        <x-primary-button class="bg-primary">{{ __('Отправить') }}</x-primary-button>
    </form>
</x-app-layout>
