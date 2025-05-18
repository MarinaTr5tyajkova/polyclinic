<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление сотрудниками</title>
    <link rel="stylesheet" href="/polyclinic/public/assets/css/employee.css?v=1.0.7">
    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
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

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Фамилия"
                           value="<?= htmlspecialchars($form_data['last_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Имя"
                           value="<?= htmlspecialchars($form_data['first_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="patronym" placeholder="Отчество"
                           value="<?= htmlspecialchars($form_data['patronym'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="login" placeholder="Логин"
                           value="<?= htmlspecialchars($form_data['login'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль">
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

<?php if (!empty($message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const errors = <?= $message ?>;
            const fieldMap = {
                'last_name': 'Фамилия',
                'first_name': 'Имя',
                'login': 'Логин',
                'password': 'Пароль'
            };

            // Удаляем старые сообщения об ошибках
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            if (errors) {
                Object.entries(errors).forEach(([field, messages]) => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        const container = input.closest('.form-group');
                        messages.forEach(msg => {
                            const errorEl = document.createElement('div');
                            errorEl.className = 'error-message';
                            errorEl.textContent = msg.replace(':field', fieldMap[field] || field);
                            container.appendChild(errorEl);
                        });
                    } else if (field === 'database') {
                        // Общие ошибки, не привязанные к полям
                        const form = document.querySelector('.add-form');
                        messages.forEach(msg => {
                            const errorEl = document.createElement('div');
                            errorEl.className = 'error-message';
                            errorEl.textContent = msg;
                            form.prepend(errorEl);
                        });
                    }
                });
            }
        });
    </script>
<?php endif; ?>




</body>
</html>
