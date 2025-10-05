<div class="card">
   <h2>Регистрация нового пользователя</h2>
   <?php if (!empty($message)): ?>
       <div class="alert alert-error"><?= $message ?></div>
   <?php endif; ?>
   <form method="post">
      <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>

      <div class="form-group">
         <label>Имя</label>
         <input type="text" name="name" placeholder="Введите имя">
      </div>

      <div class="form-group">
         <label>Логин</label>
         <input type="text" name="login" placeholder="Введите логин">
      </div>

      <div class="form-group">
         <label>Пароль</label>
         <input type="password" name="password" placeholder="Введите пароль">
      </div>

      <div class="form-group">
         <label>Телефон</label>
         <input type="text" name="phone" placeholder="Введите телефон">
      </div>

      <div class="form-group">
         <label>Email</label>
         <input type="email" name="email" placeholder="Введите email">
      </div>

      <div class="form-group">
         <label>Роль</label>
         <select name="role">
            <option value="Пациент" selected>Пациент</option>
            <option value="Врач">Врач</option>
            <option value="Администратор">Администратор</option>
         </select>
      </div>

      <button class="btn btn-success">СОХРАНИТЬ</button>
   </form>
</div>
