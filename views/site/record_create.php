<?php if (!empty($message)): ?>
    <p style="color: #d93025; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post" action="/polyclinic/record" class="record-formw">
    <label>Врач:</label>
    <select name="doctor_id" required>
        <option value="">-- Выберите врача --</option>
        <?php foreach ($doctors as $doctor): ?>
            <option value="<?= $doctor->id ?>">
                <?= htmlspecialchars($doctor->last_name . ' ' . $doctor->first_name) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Пациент:</label>
    <select name="patient_id" required>
        <option value="">-- Выберите пациента --</option>
        <?php foreach ($patients as $patient): ?>
            <option value="<?= $patient->id ?>">
                <?= htmlspecialchars($patient->last_name . ' ' . $patient->first_name) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Дата записи:</label>
    <input type="date" name="record_date" required>

    <label>Время записи:</label>
    <input type="time" name="record_time" required>

    <label>Статус:</label>
    <select name="status">
        <option value="complete">Действует</option>
        <option value="canceled">Отменено</option>
    </select>

    <button type="submit">Добавить запись</button>
</form>
