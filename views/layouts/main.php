<?php
/** @var string|null $content */
// Проверяем, является ли текущий URL страницей входа
$isLoginPage = str_contains($_SERVER['REQUEST_URI'], '/login');
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/polyclinic/public/assets/css/styles.css">
    <title>Онлайн-регистратура</title>
</head>
<body class="<?= $isLoginPage ? 'login-page' : '' ?>">
<?php if (!$isLoginPage): ?>
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-a">Электронная регистратура</div>
            <div class="logo-b">Томской области</div>
        </div>

        <div class="user-info">
            <div class="user-avatar">
                <img src="/polyclinic/public/assets/images/default-avatar.svg" alt="Аватар">
            </div>
            <div class="user-details">
                <div class="user-name">
                    <?= app()->auth->getUserFullName() ?>
                </div>
                <div class="user-role <?= app()->auth->user()->role ?>">
                    <?= app()->auth->user()->role === 'admin' ? 'Администратор' : 'Работник регистратуры' ?>
                </div>
            </div>
        </div>

        <?php if (app()->auth::check()): ?>
            <ul class="nav-menu">
                <?php if (app()->auth->user()->isAdmin()): ?>
                    <!-- Ссылки для администратора -->
                    <li><a href="/polyclinic/admin/employees">Управление сотрудниками</a></li>
                <?php elseif (app()->auth->user()->isEmployee()): ?>
                    <!-- Ссылки для сотрудника -->
                    <li><img src="/polyclinic/public/assets/images/patient.svg" alt="Patient">
                        <a href="/polyclinic/patient">Пациенты</a></li>
                    <li><img src="/polyclinic/public/assets/images/doctor.svg" style="margin-left: -2px" alt="Doctor">
                        <a href="/polyclinic/doctor"  style="margin-left: 3px" >Врачи</a></li>
                    <li><img src="/polyclinic/public/assets/images/record.svg" alt="Record">
                        <a href="/polyclinic/record">Записи</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <?php if (app()->auth::check()): ?>
            <a href="/polyclinic/logout" class="btn-logout">Выйти</a>
        <?php else: ?>
            <a href="/polyclinic/login" class="btn-login">Войти</a>
        <?php endif; ?>
    </aside>
<?php endif; ?>

<div class="container">
    <?= $content ?? ''; ?>
</div>
</body>
</html>