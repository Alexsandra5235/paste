<x-app-layout>
    <div class="container">
        <h1 class="text-center mb-4">Регистрация</h1>
        <form action="{{ route('paste.user.store') }}" method="POST">
            @csrf
            <div class="form-group mb-2">
                <label for="user_name">Имя пользователя</label>
                <input class="form-control bg-dark" type="text" id="user_name" name="user_name" value="{{ Auth::user()->name }}" required>
            </div>
            <div class="form-group mb-2">
                <label for="user_password">Пароль</label>
                <input class="form-control bg-dark" type="password" id="user_password" name="user_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
    </div>
</x-app-layout>
