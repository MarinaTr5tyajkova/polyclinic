<?php if (!empty($message)): ?>
    <p style="color: #d93025; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post" action="/polyclinic/record_create" class="record-formw">
    <label>Врач:</label>
    <select name="doctor_id" required>
        <option value="">-- Выберите врача --</option>
        <?php foreach ($doctors as $doctor): ?>
            <option value="<?= $doctor->id ?>"
                <?= isset($form_data['doctor_id']) && $form_data['doctor_id'] == $doctor->id ? 'selected' : '' ?>>
                <?= htmlspecialchars($doctor->last_name . ' ' . $doctor->first_name) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Пациент:</label>
    <select name="patient_id" required>
        <option value="">-- Выберите пациента --</option>
        <?php foreach ($patients as $patient): ?>
            <option value="<?= $patient->id ?>"
                <?= isset($form_data['patient_id']) && $form_data['patient_id'] == $patient->id ? 'selected' : '' ?>>
                <?= htmlspecialchars($patient->last_name . ' ' . $patient->first_name) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Дата записи:</label>
    <input type="date" name="record_date"
           value="<?= isset($form_data['record_date']) ? htmlspecialchars($form_data['record_date']) : '' ?>"
           required>

    <label>Время записи:</label>
    <input type="time" name="record_time"
           value="<?= isset($form_data['record_time']) ? htmlspecialchars($form_data['record_time']) : '' ?>"
           required>

    <label>Статус:</label>
    <select name="status">
        <option value="complete" <?= isset($form_data['status']) && $form_data['status'] == 'complete' ? 'selected' : '' ?>>Действует</option>
        <option value="canceled" <?= isset($form_data['status']) && $form_data['status'] == 'canceled' ? 'selected' : '' ?>>Отменено</option>
    </select>

    <button type="submit">Добавить запись</button>
</form>