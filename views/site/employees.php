<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Управление сотрудниками</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/employee.css?v=1.0.5">
    <style>
        .message-box {
            padding: 10px;
            margin: 10px 0;
            background: #f0f0f0;
            border: 1px solid #ddd;
            text-align: center;
            display: <?= isset($message) ? 'block' : 'none' ?>;
        }
    </style>
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
<div class="main-content">
    <div class="content-wrapper">
        <div class="employees-table">
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
                        <td><?= htmlspecialchars($employee->user_id) ?></td>
                        <td>
                            <?= htmlspecialchars($employee->last_name) ?>
                            <?= htmlspecialchars($employee->first_name) ?>
                            <?= htmlspecialchars($employee->patronym ?? '') ?>
                        </td>
                        <td><?= htmlspecialchars($employee->user->login) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete_id" value="<?= $employee->user_id ?>"> <!-- Передаем user_id -->
                                <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" onclick="this.closest('form').submit()">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="add-form">
            <h3>Добавить нового сотрудника</h3>
            <form method="POST">
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
                <button type="submit" class="btn-submit">Добавить</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>