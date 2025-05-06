<h1>Записи на прием</h1>
<table>
    <tr>
        <th>Статус</th>
        <th>Пациент</th>
        <th>Врач</th>
        <th>Дата</th>
        <th>Время</th>
    </tr>
    <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record->status) ?></td>
            <td>
                <?= htmlspecialchars($record->patient->last_name ?? '') ?>
                <?= htmlspecialchars($record->patient->first_name ?? '') ?>
                <?= htmlspecialchars($record->patient->patronym ?? '') ?>
            </td>
            <td>
                <?= htmlspecialchars($record->doctor->last_name ?? '') ?>
                <?= htmlspecialchars($record->doctor->first_name ?? '') ?>
                <?= htmlspecialchars($record->doctor->patronym ?? '') ?>
                (<?= htmlspecialchars($record->doctor->specialization ?? '') ?>)
            </td>
            <td><?= htmlspecialchars($record->date) ?></td>
            <td><?= htmlspecialchars($record->time) ?></td>
        </tr>
    <?php endforeach; ?>
</table>