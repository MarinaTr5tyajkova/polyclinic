<!doctype html>
<html lang="ru">
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

    <?php if (app()->auth::check()): ?>
        <div class="user-info">
            <div class="user-name"><?= app()->auth->user()->name ?></div>
            <div class="user-role"><?= app()->auth->user()->role === 'admin' ? 'Администратор' : 'Работник регистратуры' ?></div>
        </div>
    <?php endif; ?>

    <?php if (app()->auth::check()): ?>
        <ul class="nav-menu">
            <li><a href="/polyclinic/record">Записи на прием</a></li>
            <?php if (app()->auth->user()->isAdmin()): ?>
                <li><a href="/polyclinic/doctor">Врачи</a></li>
                <li><a href="/polyclinic/patient">Пациенты</a></li>
                <li><a href="/polyclinic/admin">Администрирование</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <?php if (app()->auth::check()): ?>
        <a href="/polyclinic/logout" class="btn-logout">Выйти</a>
    <?php else: ?>
        <a href="/polyclinic/login" class="btn-login">Войти</a>
    <?php endif; ?>
</aside>

<div class="container">
    <?= $content ?? ''; ?>
</div>
</body>
</html>