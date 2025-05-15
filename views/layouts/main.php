<?php
// Проверка страницы для добавления класса body
$isLoginPage = str_contains($_SERVER['REQUEST_URI'], '/login');
$isEmployeePage = str_contains($_SERVER['REQUEST_URI'], '/employee');

$user = app()->auth->user();

// Проверяем, что $user не null перед вызовом relationLoaded и load
if ($user !== null) {
    if (!$user->relationLoaded('employee')) {
        $user->load('employee');
    }
    $employee = $user->employee;
} else {
    $employee = null;
}

// Получаем путь к аватару
$avatarPath = $employee ? $employee->getAvatarPath() : '/polyclinic/public/assets/images/default-avatar.svg';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Онлайн-регистратура</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/main.css?v=1.8.12" />
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
                    <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Аватар" class="user-avatar-img" />
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars(app()->auth->getUserFullName() ?? '') ?></div>
                    <div class="user-role <?= htmlspecialchars($user->role ?? '') ?>">
                        <?= ($user && $user->role === 'admin') ? 'Администратор' : 'Работник регистратуры' ?>
                    </div>
                </div>
            </div>

            <?php if (app()->auth::check()): ?>
                <ul class="nav-menu">
                    <?php if ($user && $user->isAdmin()): ?>
                        <li><a href="/polyclinic/admin/employees">Управление сотрудниками</a></li>
                    <?php elseif ($user && $user->isEmployee()): ?>
                        <li>
                            <img src="/polyclinic/public/assets/images/patient.svg" alt="Пациенты" />
                            <a href="/polyclinic/patient">Пациенты</a>
                        </li>
                        <li>
                            <img src="/polyclinic/public/assets/images/doctor.svg" alt="Врачи" style="margin-left: -3px" />
                            <a href="/polyclinic/doctor">Врачи</a>
                        </li>
                        <li>
                            <img src="/polyclinic/public/assets/images/record.svg" alt="Записи" />
                            <a href="/polyclinic/record">Записи</a>
                        </li>
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
    <div class="containers">
        <?= $content ?? ''; ?>
    </div>
<?php endif; ?>
</body>
</html>
