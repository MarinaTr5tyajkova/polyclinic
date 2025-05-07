<div class="search-section">
    <div class="search-group">
        <label>Поиск записей по врачу:</label><br>
        <input type="text" placeholder="Введите ФИО врача">
    </div>
    <div class="search-group">
        <label>Поиск записей по пациенту:</label><br>
        <input type="text" placeholder="Введите ФИО пациента">
    </div>
</div>

<table>
    <tr>
        <th>Специализация</th>
        <th>Врач</th>
        <th>Пациент</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Статус</th>
    </tr>
    <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record->doctor->specialization ?? '') ?></td>
            <td>
                <?= htmlspecialchars($record->doctor->last_name ?? '') ?>
                <?= htmlspecialchars($record->doctor->first_name ?? '') ?>
                <?= htmlspecialchars($record->doctor->patronym ?? '') ?>
            </td>
            <td>
                <?= htmlspecialchars($record->patient->last_name ?? '') ?>
                <?= htmlspecialchars($record->patient->first_name ?? '') ?>
                <?= htmlspecialchars($record->patient->patronym ?? '') ?>
            </td>
            <td><?= date('d.m.Y', strtotime($record->date)) ?></td>
            <td><?= htmlspecialchars($record->time) ?></td>
            <td class="<?= $record->status === 'Отменен' ? 'status-canceled' : 'status-confirmed' ?>">
                <?= htmlspecialchars($record->status) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>