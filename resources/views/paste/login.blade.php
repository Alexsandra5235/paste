<x-app-layout>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('errors'))
            <div class="alert alert-danger">
                {{ session('errors') }}
            </div>
        @endif
        <h1 class="text-center mb-4">Вход в аккаунт Pastebin</h1>
        <form action="{{ route('paste.user.store') }}" method="POST">
            @csrf
            <div class="form-group mb-2">
                <label for="user_name">Имя пользователя</label>
                <input class="form-control bg-dark" type="text" id="user_name" name="user_name" required>
            </div>
            <div class="form-group mb-2">
                <label for="user_password">Пароль</label>
                <input class="form-control bg-dark" type="password" id="user_password" name="user_password" required>
            </div>
            <div class="form-group mb-2">
                <small>После авторизации в аккаунте Pastebin вы можете публиковать пасты от своего имени. Опубликовать пасту можно перейдя по ссылки в пункте меню или <a class="link-info" href="{{ route('paste.index') }}">нажав сюда.</a></small>
            </div>
            <button type="submit" class="btn btn-primary">Войти в аккаунт</button>
        </form>
    </div>
</x-app-layout>
