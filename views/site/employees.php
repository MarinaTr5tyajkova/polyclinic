<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Управление сотрудниками</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/employee.css?v=1.0.5">
</head>
<body>
<!-- Шапка -->
<header class="header">
    <div class="login-logo">
        <img src="/polyclinic/public/assets/images/logo.svg" alt="Логотип">
        <div class="logo-text">
            <div class="logo-a">Электронная регистратура</div>
            <div class="logo-b">Томской области</div>
        </div>
    </div>
    <div class="user-panel">
        <div class="user-avatar">
            <img src="/polyclinic/public/assets/images/default-avatar.svg" alt="Аватар">
        </div>
        <div class="user-name">
            <?= app()->auth->getUserFullName() ?>
        </div>

        <?php if (app()->auth::check()): ?>
            <a href="/polyclinic/logout" class="btn-logout">Выйти</a>
        <?php else: ?>
            <a href="/polyclinic/login" class="btn-login">Войти</a>
        <?php endif; ?>
    </div>
</header>
<!-- Основной контент -->
<div class="main-content">
    <div class="content-wrapper">
        <!-- Таблица сотрудников -->
        <div class="employees-table">
            <?php if ($message): ?>
                <div id="message" class="message" style="display: none;">
                    <span id="message-text"></span>
                    <span id="close-message" class="close-btn">×</span> <!-- Крестик для закрытия -->
                </div>
            <?php endif; ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee->user_id) ?> </td>
                        <td><?= htmlspecialchars($employee->last_name) ?>
                            <?= htmlspecialchars($employee->first_name) ?>
                            <?= htmlspecialchars($employee->patronym ?? '') ?></td>
                        <td><?= htmlspecialchars($employee->user->login) ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить сотрудника?');">
                                <input type="hidden" name="delete_id" value="<?= $employee->user_id ?>"> <!-- Передаем user_id -->
                                <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" onclick="this.closest('form').submit()">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Форма добавления -->
        <div class="add-form">
            <p>Добавить нового сотрудника</p>
            <form method="POST" action="/admin/employees" class="form-group-all">
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Фамилия" required>
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Имя" required>
                </div>
                <div class="form-group">
                    <input type="text" name="patronym" placeholder="Отчество">
                </div>
                <div class="form-group">
                    <input type="text" name="login" placeholder="Логин" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" required>
                </div>
                <button type="submit" class="submit-btn">Добавить сотрудника</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>