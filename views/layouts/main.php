<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/polyclinic/public/assets/css/styles.css">
    <title>Онлайн-регистратура</title>
</head>
<body>
<aside class="sidebar">
    <div class="logo">
        <div class="logo-a">Электронная регистратура</div>
        <div class="logo-b">Томской области</div>
    </div>

    <div class="user-info">
        <div class="user-name">Наталия Олеговна Ф.</div>
    </div>

    <ul class="nav-menu">
        <li><a href="#">Записи на прием</a></li>
        <li><a href="#">Врачи</a></li>
        <li><a href="#">Пациенты</a></li>
    </ul>

    <a href="#" class="btn-logout">Выйти</a>
</aside>

<div class="container">
    <?= $content ?? ''; ?>
</div>
</body>
</html>