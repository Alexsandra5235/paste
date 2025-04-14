<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Simple header</span>
        </a>

        <ul class="nav nav-pills me-3">
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link active" aria-current="page">Home</a></li>
        </ul>

        @if(Auth::user()->inRole('admin'))
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="/admin" class="nav-link active" aria-current="page">Admin panel</a></li>
            </ul>
        @endif

        <div class="dropdown text-end mt-2 mx-3">
            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu text-small" style="">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Профиль</a></li>
                <li><a class="dropdown-item" href="{{ route('paste.index') }}">Добавить новую пасту</a></li>
                <li><a class="dropdown-item" href="{{ route('paste.user.index') }}">Авторизовать профиль Pastebin</a></li>
                <li><a class="dropdown-item" href="{{ route('user.pastes') }}">Мои Pastes</a></li>
            </ul>
        </div>
        <form class="nav-item mt-2 me-2" method="post" action="{{ route('logout') }}">
            @csrf
            <input type="submit" value="Выйти">
        </form>
    </header>
</div>
