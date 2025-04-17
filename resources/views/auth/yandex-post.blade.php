<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подождите...</title>
    <meta name="robots" content="noindex,nofollow">
</head>
<body>
<form id="yandexAuthForm" method="POST" action="{{ route('login.yandex.authorize') }}">
    @csrf
    <input type="hidden" name="code" value="{{ $code }}">
</form>

<script>
    document.getElementById('yandexAuthForm').submit();
</script>
</body>
</html>
