<div class="card">
   <h2>Регистрация пациента</h2>
   <?php if (!empty($message)): ?>
       <div class="alert alert-error"><?= $message ?></div>
   <?php endif; ?>
   <form method="post">
      <?php use Src\Auth\Auth; ?>
      <input name="csrf_token" type="hidden" value="<?= Auth::generateCSRF() ?>"/>
      <?php $old = $old ?? []; ?>

      <div class="form-group">
         <label>Имя</label>
         <input type="text" name="name" placeholder="Введите имя" value="<?= $old['name'] ?? '' ?>">
      </div>

      <div class="form-group">
         <label>Логин</label>
         <input type="text" name="login" placeholder="Введите логин" value="<?= $old['login'] ?? '' ?>">
      </div>

      <div class="form-group">
         <label>Пароль</label>
         <input type="password" name="password" placeholder="Введите пароль">
      </div>

      <div class="form-group">
         <label>Телефон</label>
         <input type="text" name="phone" placeholder="Введите телефон" value="<?= $old['phone'] ?? '' ?>">
      </div>

      <div class="form-group">
         <label>Email</label>
         <input type="email" name="email" placeholder="Введите email" value="<?= $old['email'] ?? '' ?>">
      </div>

      <div class="form-group">
         <label>Дата рождения</label>
         <input type="date" name="birth_date" value="<?= $old['birth_date'] ?? '' ?>">
      </div>

      <div class="form-group">
         <label>Номер полиса ОМС</label>
         <input type="text" name="policy_number" placeholder="Введите номер полиса" value="<?= $old['policy_number'] ?? '' ?>">
      </div>

      <button class="btn btn-success">СОХРАНИТЬ</button>
   </form>
</div>
