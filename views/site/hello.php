<div class="card hero">
    <h1><?= $message ?? 'Информационная система' ?></h1>
    <p class="lead">
        Планируйте приёмы, контролируйте расписание врачей и управляйте нагрузкой клиники в едином окне.
    </p>
    <div class="hero-actions">
        <a href="<?= app()->route->getUrl('/login') ?>" class="btn btn-primary">Войти в систему</a>
        <a href="<?= app()->route->getUrl('/signup') ?>" class="btn btn-secondary">Зарегистрировать пациента</a>
    </div>
</div>

<div class="grid">
    <?php foreach ($highlights ?? [] as $item): ?>
        <div class="card">
            <h3><?= $item['title'] ?></h3>
            <p><?= $item['description'] ?></p>
        </div>
    <?php endforeach; ?>
</div>
