<div class="dashboard">
    <div class="card">
        <h2>Здравствуйте, <?= $user->name ?>!</h2>
        <p>Здесь собраны ключевые показатели по вашему уровню доступа.</p>
        <div class="stats-grid">
            <div class="stat-card">
                <span>Врачи</span>
                <strong><?= $stats['totalDoctors'] ?? 0 ?></strong>
            </div>
            <div class="stat-card">
                <span>Пациенты</span>
                <strong><?= $stats['totalPatients'] ?? 0 ?></strong>
            </div>
            <div class="stat-card">
                <span>Ближайшие визиты</span>
                <strong><?= $stats['upcomingAppointments'] ?? 0 ?></strong>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Ближайшие приёмы</h3>
            <a href="<?= app()->route->getUrl('/appointments') ?>" class="btn btn-link">Перейти к расписанию</a>
        </div>
        <?php if ($appointments->isEmpty()): ?>
            <p>Нет запланированных приёмов.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Услуга</th>
                        <th>Пациент</th>
                        <th>Врач</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= $appointment->appointment_date ?></td>
                            <td><?= $appointment->appointment_time ?></td>
                            <td><?= $appointment->service_type ?></td>
                            <td><?= $appointment->patient ? $appointment->patient->name : '—' ?></td>
                            <td><?= $appointment->doctor ? $appointment->doctor->name : '—' ?></td>
                            <td><span class="badge"><?= $appointment->status ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>


