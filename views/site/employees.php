<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление сотрудниками</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/employee.css?v=1.0.7">
    <style>
        .message-box {
            padding: 10px;
            margin: 10px 0;
            background: #f0f0f0;
            border: 1px solid #ddd;
            text-align: center;
        }
        .errors {
            background-color: #ffe6e6;
            border: 1px solid #ff0000;
            padding: 10px;
            margin-bottom: 15px;
            color: #a70000;
            list-style-type: disc;
        }
        .errors li {
            margin-left: 20px;
        }
    </style>
</head>
<body>
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
            <?= htmlspecialchars(app()->auth->getUserFullName()) ?>
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
                    <th>Аватар</th>
                    <th>Действия</th>
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
                            <img src="<?= htmlspecialchars($employee->getAvatarPath()) ?>" alt="Аватар" style="width:40px; height:40px; border-radius:50%;">
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Удалить сотрудника?');" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($employee->user_id) ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer;">
                                    <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" style="width:20px; height:20px;">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="add-form">
            <h3>Добавить нового сотрудника</h3>

            <!-- Вывод ошибок валидации с указанием полей -->
            <?php if (!empty($errors) && is_array($errors)): ?>
                <ul class="errors">
                    <?php foreach ($errors as $field => $fieldErrors): ?>
                        <?php foreach ($fieldErrors as $error): ?>
                            <li><strong><?= htmlspecialchars($fieldNames[$field] ?? $field) ?>:</strong> <?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Вывод общего сообщения -->
            <?php if (!empty($message) && empty($errors)): ?>
                <div class="message-box"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" novalidate>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Фамилия" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Имя" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="patronym" placeholder="Отчество" value="<?= htmlspecialchars($_POST['patronym'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="login" placeholder="Логин" required
                           value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" required>
                </div>
                <div class="form-group">
                    <label>Аватар (необязательно)</label>
                    <input type="file" name="avatar" accept="image/*">
                </div>
                <button type="submit" class="btn-submit">Добавить</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
