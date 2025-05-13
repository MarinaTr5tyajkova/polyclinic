<?php
/** @var array $employees */
/** @var string|null $message */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Управление сотрудниками</title>
</head>
<body>
<h1>Список сотрудников регистратуры</h1>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="/admin/employees">
    <h2>Добавить нового сотрудника</h2>
    <label>
        Фамилия:
        <input type="text" name="last_name" required>
    </label>
    <label>
        Имя:
        <input type="text" name="first_name" required>
    </label>
    <label>
        Отчество:
        <input type="text" name="patronym">
    </label>
    <label>
        Логин:
        <input type="text" name="login" required>
    </label>
    <label>
        Пароль:
        <input type="password" name="password" required>
    </label>
    <button type="submit">Добавить</button>
</form>

<h2>Текущие сотрудники</h2>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Логин</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?= htmlspecialchars($employee->id) ?></td>
            <td><?= htmlspecialchars($employee->last_name) ?></td>
            <td><?= htmlspecialchars($employee->first_name) ?></td>
            <td><?= htmlspecialchars($employee->patronym ?? '') ?></td>
            <td><?= htmlspecialchars($employee->user->login) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="delete_id" value="<?= $employee->id ?>">
                    <button type="submit">Удалить</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>