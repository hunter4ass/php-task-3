<div class="card">
   <h2>Запись на прием</h2>
   <?php if (!empty($message)): ?>
       <div class="alert alert-error"><?= $message ?></div>
   <?php endif; ?>
   <form method="post" enctype="multipart/form-data">
       <?php use Src\Auth\Auth;
       $old = $old ?? []; ?>
       <input name="csrf_token" type="hidden" value="<?= Auth::generateCSRF() ?>"/>

       <?php if (Auth::user()->role === 'Администратор'): ?>
           <div class="form-group">
               <label>Пациент</label>
               <select name="patient_id" required>
                   <option value="">Выберите пациента</option>
                   <?php foreach ($patients ?? [] as $patient): ?>
                       <option value="<?= $patient->id ?>" <?= ($old['patient_id'] ?? '') == $patient->id ? 'selected' : '' ?>>
                           <?= $patient->name ?>
                       </option>
                   <?php endforeach; ?>
               </select>
           </div>
       <?php endif; ?>

       <div class="form-group">
           <label>Врач</label>
           <select name="doctor_id" required>
               <option value="">Выберите врача</option>
               <?php foreach ($doctors as $doctor): ?>
                   <option value="<?= $doctor->id ?>" <?= ($old['doctor_id'] ?? '') == $doctor->id ? 'selected' : '' ?>>
                       <?= $doctor->name ?>
                   </option>
               <?php endforeach; ?>
           </select>
       </div>

       <div class="form-group">
           <label>Тип услуги</label>
           <select name="service_type" required>
               <option value="">Выберите услугу</option>
               <?php foreach ($services as $service): ?>
                   <option value="<?= $service ?>" <?= ($old['service_type'] ?? '') === $service ? 'selected' : '' ?>>
                       <?= $service ?>
                   </option>
               <?php endforeach; ?>
           </select>
       </div>

       <div class="form-group">
           <label>Дата приема</label>
           <input type="date" name="appointment_date" required value="<?= $old['appointment_date'] ?? '' ?>">
       </div>

       <div class="form-group">
           <label>Время приема</label>
           <input type="time" name="appointment_time" required value="<?= $old['appointment_time'] ?? '' ?>">
       </div>

       <div class="form-group">
           <label>Жалобы / причина обращения</label>
           <textarea name="complaint" rows="3" placeholder="Кратко опишите симптомы"><?= $old['complaint'] ?? '' ?></textarea>
       </div>

       <?php if (Auth::user()->role === 'Администратор'): ?>
           <div class="form-group">
               <label>Кабинет</label>
               <input type="text" name="room" value="<?= $old['room'] ?? '' ?>" placeholder="Например, 302А">
           </div>
       <?php endif; ?>

       <div class="form-group">
           <label>Комментарий для врача (необязательно)</label>
           <textarea name="notes" rows="3"><?= $old['notes'] ?? '' ?></textarea>
       </div>

       <div class="form-group">
           <label>Медицинские документы (jpg/png, до 2 МБ)</label>
           <input type="file" name="attachment" accept="image/*">
       </div>

       <button class="btn btn-primary">Записаться</button>
       <a href="<?= app()->route->getUrl('/appointments') ?>" class="btn btn-secondary">Отмена</a>
   </form>
</div>

