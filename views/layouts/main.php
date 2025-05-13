<?php
// Проверка страницы для добавления класса body
$isLoginPage = str_contains($_SERVER['REQUEST_URI'], '/login');
$isEmployeePage = str_contains($_SERVER['REQUEST_URI'], '/employee');
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Онлайн-регистратура</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/main.css?v=1.0.6" />
</head>
<body class="<?= $isLoginPage ? 'login-page' : '' ?>">
<?php if (!$isLoginPage && !$isEmployeePage): ?>
    <div class="layout-wrapper">
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-a">Электронная регистратура</div>
                <div class="logo-b">Томской области</div>
            </div>

            <div class="user-info">
                <div class="user-avatar">
                    <img src="/polyclinic/public/assets/images/default-avatar.svg" alt="Аватар" />
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars(app()->auth->getUserFullName()) ?></div>
                    <div class="user-role <?= htmlspecialchars(app()->auth->user()->role) ?>">
                        <?= app()->auth->user()->role === 'admin' ? 'Администратор' : 'Работник регистратуры' ?>
                    </div>
                </div>
            </div>

            <?php if (app()->auth::check()): ?>
                <ul class="nav-menu">
                    <?php if (app()->auth->user()->isAdmin()): ?>
                        <li><a href="/polyclinic/admin/employees">Управление сотрудниками</a></li>
                    <?php elseif (app()->auth->user()->isEmployee()): ?>
                        <li><img src="/polyclinic/public/assets/images/patient.svg" alt="Пациенты" />
                            <a href="/polyclinic/patient">Пациенты</a></li>
                        <li><img src="/polyclinic/public/assets/images/doctor.svg" alt="Врачи" style="margin-left: 3px" />
                            <a href="/polyclinic/doctor">Врачи</a></li>
                        <li><img src="/polyclinic/public/assets/images/record.svg" alt="Записи" />
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

        <div class="container">
            <?= $content ?? ''; ?>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <?= $content ?? ''; ?>
    </div>
<?php endif; ?>
</body>
</html>
