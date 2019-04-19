<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Підтвердження реєстрації</title>
</head>
<body>


<h1>Дякуємо за реєстрацію!</h1>


<p>
    Нам просто потрібно, щоб ви <a href='{{ url("register/confirm/{$user->token}") }}'>підтвердити вашу  email адресу</a> !
</p>


</body>
</html>