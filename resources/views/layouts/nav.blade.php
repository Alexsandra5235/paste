<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Simple header</span>
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link active" aria-current="page">Home</a></li>
        </ul>
        <div class="dropdown text-end mt-2 mx-3">
            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu text-small" style="">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Профиль</a></li>
                <li><a class="dropdown-item" href="{{ route('paste.index') }}">Добавить новую пасту</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Выход</a></li>
            </ul>
        </div>
    </header>
</div>
