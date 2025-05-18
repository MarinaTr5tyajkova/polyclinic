<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .form-group.has-error input {
            border-color: #dc3545;
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }
    </style>
</head>
<body>

<div class="search-group">
    <form method="GET" action="/polyclinic/doctor/search" class="search-groupe" style="display: flex; align-items: center">
        <div class="form-group" style="width: 350px; margin: 0">
            <input type="text" name="search_query" placeholder="Введите ФИО или специализацию врача" value="<?= htmlspecialchars($search_query ?? '') ?>" required />
        </div>
        <button type="submit" class="btn_submit">Найти</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Специализация</th>
        <th>Должность</th>
        <th>Дата рождения</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($doctors as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor->last_name ?? '') ?>
                <?= htmlspecialchars($doctor->first_name ?? '') ?>
                <?= htmlspecialchars($doctor->patronym ?? '') ?>
            </td>
            <td><?= htmlspecialchars($doctor->specialization ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->post ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->birthday ?? '') ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= $doctor->id?>">
                    <button type="submit" style="background:none; border:none; cursor:pointer;" title="Удалить врача">
                        <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" style="width:20px; height:20px;">
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($doctors)): ?>
        <tr><td colspan="7" style="text-align:center; padding: 20px;">Врачи не найдены</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="add-form">
    <h3>Добавить нового врача</h3>
    <form method="POST" action="/polyclinic/doctor">
        <div class="form-group <?= !empty($errors['last_name']) ? 'has-error' : '' ?>">
            <input type="text" name="last_name" placeholder="Фамилия" required
                   value="<?= htmlspecialchars($form_data['last_name'] ?? '') ?>" />
            <?php if (!empty($errors['last_name'])): ?>
                <?php foreach ($errors['last_name'] as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="form-group <?= !empty($errors['first_name']) ? 'has-error' : '' ?>">
            <input type="text" name="first_name" placeholder="Имя" required
                   value="<?= htmlspecialchars($form_data['first_name'] ?? '') ?>" />
            <?php if (!empty($errors['first_name'])): ?>
                <?php foreach ($errors['first_name'] as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <input type="text" name="patronym" placeholder="Отчество"
                   value="<?= htmlspecialchars($form_data['patronym'] ?? '') ?>" />
        </div>

        <div class="form-group <?= !empty($errors['specialization']) ? 'has-error' : '' ?>">
            <input type="text" name="specialization" placeholder="Специализация" required
                   value="<?= htmlspecialchars($form_data['specialization'] ?? '') ?>" />
            <?php if (!empty($errors['specialization'])): ?>
                <?php foreach ($errors['specialization'] as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="form-group <?= !empty($errors['post']) ? 'has-error' : '' ?>">
            <input type="text" name="post" placeholder="Должность" required
                   value="<?= htmlspecialchars($form_data['post'] ?? '') ?>" />
            <?php if (!empty($errors['post'])): ?>
                <?php foreach ($errors['post'] as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="form-group <?= !empty($errors['birthday']) ? 'has-error' : '' ?>">
            <input type="date" name="birthday" placeholder="Дата рождения" required
                   value="<?= htmlspecialchars($form_data['birthday'] ?? '') ?>" />
            <?php if (!empty($errors['birthday'])): ?>
                <?php foreach ($errors['birthday'] as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Добавить</button>
    </form>
</div>

</body>
</html>
