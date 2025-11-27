<div class="card">
    <h2>Добавление пользователя</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-error"><?= $message ?></div>
    <?php endif; ?>
    <?php $old = $old ?? []; ?>
    <form method="post">
        <?php use Src\Auth\Auth; ?>
        <input name="csrf_token" type="hidden" value="<?= Auth::generateCSRF() ?>"/>

        <div class="form-group">
            <label>ФИО</label>
            <input type="text" name="name" value="<?= $old['name'] ?? '' ?>" placeholder="Например, Иванов Иван Иванович">
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" value="<?= $old['login'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label>Роль</label>
                <select name="role">
                    <option value="Администратор" <?= ($old['role'] ?? '') === 'Администратор' ? 'selected' : '' ?>>Администратор</option>
                    <option value="Врач" <?= ($old['role'] ?? '') === 'Врач' ? 'selected' : '' ?>>Врач</option>
                    <option value="Пациент" <?= ($old['role'] ?? '') === 'Пациент' ? 'selected' : '' ?>>Пациент</option>
                </select>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="phone" value="<?= $old['phone'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $old['email'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Дата рождения</label>
                <input type="date" name="birth_date" value="<?= $old['birth_date'] ?? '' ?>">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Номер полиса (для пациентов)</label>
                <input type="text" name="policy_number" value="<?= $old['policy_number'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Специализация (для врачей)</label>
                <input type="text" name="specialization" value="<?= $old['specialization'] ?? '' ?>" placeholder="Терапевт, хирург...">
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-success">Сохранить</button>
            <a href="<?= app()->route->getUrl('/users') ?>" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>


