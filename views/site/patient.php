<div class="main-content">
    <div class="content-wrapper">
        <!-- Поисковая строка -->
        <div class="search-group">
            <form method="GET" action="/polyclinic/patient/search" class="search-groupe" style="display: flex; align-items: center">
                <div class="form-group" style="width: 350px; margin: 0">
                    <input type="text" name="search_query" placeholder="Введите ФИО пациента" required>
                </div>
                <button type="submit" class="btn_submit">Найти</button>
            </form>
        </div>

        <!-- Таблица пациентов -->
        <div class="patients-table">
            <table class="table_patient">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Дата рождения</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($patient->last_name ?? '') ?>
                            <?= htmlspecialchars($patient->first_name ?? '') ?>
                            <?= htmlspecialchars($patient->patronym ?? '') ?>
                        </td>
                        <td><?= htmlspecialchars($patient->birthday ?? '') ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $patient->id ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer;">
                                    <img src="/polyclinic/public/assets/images/trashcan.svg" alt="Удалить" title="Удалить пациента">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Форма добавления пациента -->
        <div class="add-form">
            <h3>Добавить нового пациента</h3>
            <form method="POST" action="/polyclinic/patient">
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Фамилия" required>
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Имя" required>
                </div>
                <div class="form-group">
                    <input type="text" name="patronym" placeholder="Отчество">
                </div>
                <div class="form-group">
                    <input type="date" name="birthday" placeholder="Дата рождения" required>
                </div>
                <button type="submit" class="btn-submit">Добавить</button>
            </form>
        </div>
    </div>
</div>
