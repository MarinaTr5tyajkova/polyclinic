<h1>Список пациентов</h1>
<table>
    <tr>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Дата рождения</th>
    </tr>
    <?php foreach ($patients as $patient): ?>
        <tr>
            <td><?= htmlspecialchars($patient->last_name ?? '') ?></td>
            <td><?= htmlspecialchars($patient->first_name ?? '') ?></td>
            <td><?= htmlspecialchars($patient->patronym ?? '') ?></td>
            <td><?= htmlspecialchars($patient->birthday ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>