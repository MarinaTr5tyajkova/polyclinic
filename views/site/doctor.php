<table>
    <tr>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Специализация</th>
        <th>Дата рождения</th>

    </tr>
    <?php foreach ($doctors as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor->last_name ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->first_name ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->patronym ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->specialization ?? '') ?></td>
            <td><?= htmlspecialchars($doctor->birthday ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>