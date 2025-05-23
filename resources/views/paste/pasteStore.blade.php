<x-app-layout>

    <div class="container mb-4">
        @if(session('success'))
            <div class="alert alert-success">
                <p>Successfully created paste.</p>
                <p>Посмотреть пасту можно по ссылке: <a class="link-info" href="{{ session('success') }}" target="_blank" rel="noopener noreferrer">Нажми, чтобы перейти</a></p>
                <small>Сохраните эту ссылку, если вы выбрали пункт загрузить анонимно, в противном случае ссылка будет утеряна. Если загружали пасту со своего аккаунта, то посмотреть ее можно в специальном пункте меню или <a class="link-info" href="{{ route('user.pastes') }}">перейдя по ссылке.</a> </small>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        <h1 class="my-2 text-center">Создать новую пасту</h1>

        <form action="{{ route('paste.store') }}" method="POST">
            @csrf

            <div class="form-group mb-2">
                <label for="paste_name">Название пасты</label>
                <input type="text" class="form-control bg-dark" id="paste_name" name="paste_name" required>
            </div>

            <div class="form-group mb-2">
                <label for="paste_code">Код пасты</label>
                <textarea class="form-control" id="paste_code" name="paste_code" rows="10" required></textarea>
            </div>

            <div class="form-group mb-2">
                <label for="paste_format">Язык программирования</label>
                <select class="form-control" id="paste_format" name="paste_format" required>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="python">Python</option>
                    <option value="html4strict">HTML</option>
                    <option value="css">CSS</option>
                    <option value="java">Java</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <label for="paste_private">Тип доступа</label>
                <select class="form-control" id="paste_private" name="paste_private" required>
                    <option value="0">Public</option>
                    @if(Auth::user()->api_key)
                        <option value="2">Private</option>
                    @endif
                    <option value="1">Unlisted</option>

                </select>
            </div>

            <div class="form-group mb-2">
                <label for="expire_date">Срок действия</label>
                <select class="form-control" id="expire_date" name="expire_date" required>
                    <option value="10M">10 минут</option>
                    <option value="1H">1 час</option>
                    <option value="1D">1 день</option>
                    <option value="1W">1 неделя</option>
                    <option value="2W">2 недели</option>
                    <option value="1M">1 месяц</option>
                    <option value="6M">6 месяцев</option>
                    <option value="1Y">1 год</option>
                    <option value="N">Без ограничения</option>
                </select>
            </div>

            @if(Auth::user()->api_key)
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="check_private" name="check_private">
                    <label class="form-check-label" for="check_private">
                        Загрузить анонимно?
                    </label>
                </div>
            @else
                <div class="form-group mb-2">
                    <small>Паста добавится анонимно. Если необходимо опубликовать пасту со своего аккаунта Pastebin, необходимо авторизоваться в нем. Для этого нужно перейти в специальный пункт меню или нажать на эту <a class="link-info" href="{{ route('paste.user.index') }}">ссылку.</a></small>
                </div>
            @endif

            <input type="submit" class="btn btn-primary" value="Создать пасту">
        </form>
    </div>
</x-app-layout>
