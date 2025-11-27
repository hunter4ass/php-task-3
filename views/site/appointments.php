<?php
use Src\Auth\Auth;
$user = Auth::user();
?>

<div class="card">
   <div class="card-header">
       <h2>Записи на прием</h2>
       <?php if ($user->role === 'Пациент' || $user->role === 'Администратор'): ?>
           <a href="<?= app()->route->getUrl('/appointments/create') ?>" class="btn btn-primary" style="max-width: 240px;">
               Запланировать новый прием
           </a>
       <?php endif; ?>
   </div>

   <form method="get" class="filters">
       <div class="form-group">
           <label>Статус</label>
           <select name="status">
               <option value="all" <?= ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' ?>>Все</option>
               <?php foreach ($statuses as $status): ?>
                   <option value="<?= $status ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>>
                       <?= $status ?>
                   </option>
               <?php endforeach; ?>
           </select>
       </div>
       <div class="form-group">
           <label>С даты</label>
           <input type="date" name="date_from" value="<?= $filters['date_from'] ?? '' ?>">
       </div>
       <div class="form-group">
           <label>По дату</label>
           <input type="date" name="date_to" value="<?= $filters['date_to'] ?? '' ?>">
       </div>
       <div class="form-group">
           <label>Поиск</label>
           <input type="text" name="search" placeholder="Пациент, врач или услуга" value="<?= $filters['search'] ?? '' ?>">
       </div>
       <button class="btn btn-secondary" style="max-width: 200px;">Применить</button>
       <a href="<?= app()->route->getUrl('/appointments') ?>" class="btn btn-link" style="max-width: 200px;">Сбросить</a>
   </form>

   <?php if ($appointments->isEmpty()): ?>
       <p>Записей пока нет.</p>
   <?php else: ?>
       <table class="table">
           <thead>
               <tr>
                   <th>Дата</th>
                   <th>Время</th>
                   <th>Услуга</th>
                   <?php if ($user->role === 'Врач' || $user->role === 'Администратор'): ?>
                       <th>Пациент</th>
                   <?php endif; ?>
                   <?php if ($user->role === 'Пациент' || $user->role === 'Администратор'): ?>
                       <th>Врач</th>
                   <?php endif; ?>
                   <th>Кабинет</th>
                   <th>Документы</th>
                   <th>Статус</th>
                   <th>Действия</th>
               </tr>
           </thead>
           <tbody>
               <?php foreach ($appointments as $appointment): ?>
                   <tr>
                       <td><?= $appointment->appointment_date ?></td>
                       <td><?= $appointment->appointment_time ?></td>
                       <td>
                           <div><?= $appointment->service_type ?></div>
                           <?php if (!empty($appointment->complaint)): ?>
                               <small class="muted"><?= $appointment->complaint ?></small>
                           <?php endif; ?>
                       </td>
                       <?php if ($user->role === 'Врач' || $user->role === 'Администратор'): ?>
                           <td><?= $appointment->patient ? $appointment->patient->name : 'Не указан' ?></td>
                       <?php endif; ?>
                       <?php if ($user->role === 'Пациент' || $user->role === 'Администратор'): ?>
                           <td><?= $appointment->doctor ? $appointment->doctor->name : 'Не указан' ?></td>
                       <?php endif; ?>
                       <td><?= $appointment->room ?? '—' ?></td>
                       <td>
                           <?php if ($appointment->attachment_path): ?>
                               <a href="<?= app()->route->getUrl('/' . $appointment->attachment_path) ?>" target="_blank" class="btn btn-sm btn-secondary" style="margin:0;">
                                   Открыть
                               </a>
                           <?php else: ?>
                               —
                           <?php endif; ?>
                       </td>
                       <td><span class="badge"><?= $appointment->status ?></span></td>
                       <td>
                           <?php if ($user->role === 'Врач' || $user->role === 'Администратор'): ?>
                               <a href="<?= app()->route->getUrl('/appointments/manage?id=' . $appointment->id) ?>" class="btn btn-sm btn-primary">
                                   Управлять
                               </a>
                           <?php endif; ?>
                           <?php if ($user->role === 'Администратор'): ?>
                               <a href="<?= app()->route->getUrl('/appointments/delete?id=' . $appointment->id) ?>" 
                                  class="btn btn-sm btn-danger"
                                  onclick="return confirm('Вы уверены, что хотите удалить эту запись?')">
                                   Удалить
                               </a>
                           <?php endif; ?>
                       </td>
                   </tr>
               <?php endforeach; ?>
           </tbody>
       </table>
   <?php endif; ?>
</div>


