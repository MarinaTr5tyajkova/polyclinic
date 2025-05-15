<form method="get" action="/polyclinic/record" style="margin-bottom: 20px;">

    <!-- Контейнер для полей поиска и кнопки Найти -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">

        <!-- Поиск по врачу -->
        <div style="flex: 1; min-width: 200px;">
            <input
                    type="text"
                    id="doctor_search"
                    name="doctor"
                    placeholder="Введите ФИО врача"
                    value="<?= htmlspecialchars($doctorSearch ?? '') ?>"
                    style="width: 100%; padding: 8px; box-sizing: border-box;"
            >
        </div>

        <!-- Поиск по пациенту -->
        <div style="flex: 1; min-width: 200px;">
            <input
                    type="text"
                    id="patient_search"
                    name="patient"
                    placeholder="Введите ФИО пациента"
                    value="<?= htmlspecialchars($patientSearch ?? '') ?>"
                    style="width: 100%; padding: 8px; box-sizing: border-box;"
            >
        </div>

        <!-- Поиск по дате -->
        <div style="min-width: 180px;">
            <input
                    type="date"
                    id="date_search"
                    name="date"
                    value="<?= htmlspecialchars($dateSearch ?? '') ?>"
                    style="width: 100%; padding: 8px; box-sizing: border-box;"
            >
        </div>

        <!-- Кнопка Найти -->
        <div>
            <button type="submit" style="padding: 10px 20px; background-color: #12B585; color: white; border: none; cursor: pointer;">
                Найти
            </button>
        </div>

    </div>

    <!-- Кнопка Добавить запись, расположена ниже -->
    <div style="margin-top: 15px;">
        <a href="/polyclinic/record_create" class="btn-add-recordes" style="display: inline-block; width: 180px; height: 60px; background-color: #06825D; color: white; text-align: center; line-height: 60px; text-decoration: none; cursor: pointer;">
            + Записать на прием
        </a>
    </div>

</form>

<table class="table_record" style="width: 100%; border-collapse: collapse;">
    <tr>
        <th>Специализация</th>
        <th>Врач</th>
        <th>Пациент</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Статус</th>
        <th></th>
        <th></th>
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
            <td><?= date('d.m.Y', strtotime($record->record_date)) ?></td>
            <td><?= htmlspecialchars($record->record_time) ?></td>
            <td class="<?= $record->status === 'Отменен' ? 'status-canceled' : 'status-confirmed' ?>">
                <?php if ($record->status === 'complete' || $record->status === 'Действует'): ?>
                    &#10004; <!-- ✓ галочка -->
                <?php elseif ($record->status === 'canceled' || $record->status === 'Отменен'): ?>
                    &#10008; <!-- ✘ крестик -->
                <?php else: ?>
                    <?= htmlspecialchars($record->status) ?>
                <?php endif; ?>
            </td>
            <td style="text-align: center;">
                <form method="post" style="display:inline;">
                    <input type="hidden" name="toggle_status_id" value="<?= $record->id ?>">
                    <button type="submit" style="background:none; border:none; cursor:pointer;" title="Удалить запись">
                        <img src="/polyclinic/public/assets/images/edit.svg" alt="Изменитьстатус" style="width:20px; height:20px;">
                    </button>
                </form>
            </td>
            <td style="text-align: center;">
                <form method="post" style="display:inline;" onsubmit="return confirm('Удалить запись?');">
                    <input type="hidden" name="delete_id" value="<?= $record->id ?>">
                    <button type="submit" style="background:none; border:none; cursor:pointer;" title="Удалить запись">
                        <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" style="width:20px; height:20px;">
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>