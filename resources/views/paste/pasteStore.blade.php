<x-app-layout>

    <div class="container">
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
                    <option value="1">Unlisted</option>
                    <option value="2">Private</option>
                </select>
            </div>

            <div class="form-group mb-2">
                <label for="expire_date">Срок действия</label>
                <select class="form-control" id="expire_date" name="expire_date" required>
                    <option value="10M">10 минут</option>
                    <option value="1H">1 час</option>
                    <option value="3H">3 часа</option>
                    <option value="1D">1 день</option>
                    <option value="1W">1 неделя</option>
                    <option value="1M">1 месяц</option>
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
                    <small>Паста добавится анонимно. Если необходимо опубликовать пасту со своего аккаунта, необходимо зайти в него. Для этого нужно зайти в специальный пункт меню.</small>
                </div>
            @endif

            <input type="submit" class="btn btn-primary" value="Создать пасту">
        </form>
    </div>
</x-app-layout>
