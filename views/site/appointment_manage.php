<div class="card">
   <h2>Управление записью</h2>
   <?php if (!empty($message)): ?>
       <div class="alert alert-error"><?= $message ?></div>
   <?php endif; ?>
   
   <form method="post" enctype="multipart/form-data">
       <?php use Src\Auth\Auth;
       $isAdmin = $user->role === 'Администратор'; ?>
       <input name="csrf_token" type="hidden" value="<?= Auth::generateCSRF() ?>"/>
       <input name="appointment_id" type="hidden" value="<?= $appointment->id ?>"/>

       <div class="form-group">
           <label>Пациент</label>
           <input type="text" value="<?= $appointment->patient ? $appointment->patient->name : 'Не указан' ?>" disabled>
       </div>

       <div class="form-group">
           <label>Врач</label>
           <input type="text" value="<?= $appointment->doctor ? $appointment->doctor->name : 'Не указан' ?>" disabled>
       </div>

       <div class="form-group">
           <label>Тип услуги</label>
           <?php if ($isAdmin): ?>
               <select name="service_type">
                   <?php foreach ($services as $service): ?>
                       <option value="<?= $service ?>" <?= $appointment->service_type === $service ? 'selected' : '' ?>>
                           <?= $service ?>
                       </option>
                   <?php endforeach; ?>
               </select>
           <?php else: ?>
               <input type="text" value="<?= $appointment->service_type ?>" readonly>
           <?php endif; ?>
       </div>

       <div class="form-group">
           <label>Дата приема</label>
           <input type="date" name="appointment_date" value="<?= $appointment->appointment_date ?>" required>
       </div>

       <div class="form-group">
           <label>Время приема</label>
           <input type="time" name="appointment_time" value="<?= $appointment->appointment_time ?>" required>
       </div>

       <div class="form-group">
           <label>Кабинет</label>
           <input type="text" name="room" value="<?= $appointment->room ?>">
       </div>

       <div class="form-group">
           <label>Статус</label>
           <select name="status" required>
               <?php foreach ($statuses as $status): ?>
                   <option value="<?= $status ?>" <?= $appointment->status === $status ? 'selected' : '' ?>><?= $status ?></option>
               <?php endforeach; ?>
           </select>
       </div>

       <div class="form-group">
           <label>Жалоба пациента</label>
           <textarea rows="4" readonly><?= $appointment->complaint ?? '—' ?></textarea>
       </div>

       <div class="form-group">
           <label>Заметки</label>
           <textarea name="notes" rows="4"><?= $appointment->notes ?? '' ?></textarea>
       </div>

       <div class="form-group">
           <label>Медицинские документы (jpg/png, до 2 МБ)</label>
           <input type="file" name="attachment" accept="image/*">
           <?php if (!empty($appointment->attachment_path)): ?>
               <p style="margin-top:10px;">
                   <a href="<?= app()->route->getUrl('/' . $appointment->attachment_path) ?>" target="_blank">Просмотреть загруженный файл</a>
               </p>
           <?php endif; ?>
       </div>

       <button class="btn btn-primary">Сохранить</button>
       <a href="<?= app()->route->getUrl('/appointments') ?>" class="btn btn-secondary">Отмена</a>
   </form>
</div>

