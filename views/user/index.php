<div class="card">
    <div class="card-header">
        <div>
            <h2>Справочник пользователей</h2>
            <p>Добавляйте врачей и пациентов, назначайте роли и отслеживайте контактные данные.</p>
        </div>
        <a href="<?= app()->route->getUrl('/users/create') ?>" class="btn btn-primary" style="max-width: 220px;">Добавить пользователя</a>
    </div>

    <form method="get" class="filters">
        <div class="form-group">
            <label>Роль</label>
            <select name="role">
                <option value="all" <?= ($filters['role'] ?? 'all') === 'all' ? 'selected' : '' ?>>Все</option>
                <option value="Администратор" <?= ($filters['role'] ?? '') === 'Администратор' ? 'selected' : '' ?>>Администраторы</option>
                <option value="Врач" <?= ($filters['role'] ?? '') === 'Врач' ? 'selected' : '' ?>>Врачи</option>
                <option value="Пациент" <?= ($filters['role'] ?? '') === 'Пациент' ? 'selected' : '' ?>>Пациенты</option>
            </select>
        </div>
        <div class="form-group">
            <label>Поиск</label>
            <input type="text" name="search" placeholder="Имя, почта или логин" value="<?= $filters['search'] ?? '' ?>">
        </div>
        <button class="btn btn-secondary" style="max-width: 200px;">Применить</button>
    </form>

    <?php if ($users->isEmpty()): ?>
        <p>Пользователи с такими параметрами не найдены.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Роль</th>
                    <th>Контакты</th>
                    <th>Дополнительно</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->name ?></td>
                        <td><span class="badge"><?= $user->role ?></span></td>
                        <td>
                            <div>Логин: <?= $user->login ?></div>
                            <div>Телефон: <?= $user->phone ?></div>
                            <div>Email: <?= $user->email ?></div>
                        </td>
                        <td>
                            <?php if ($user->role === 'Врач'): ?>
                                Специализация: <?= $user->specialization ?? '—' ?>
                            <?php else: ?>
                                Полис: <?= $user->policy_number ?? '—' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>


