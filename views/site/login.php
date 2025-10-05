<div class="card">
   <h2>Авторизация</h2>
   <?php if (!empty($message)): ?>
       <div class="alert alert-error"><?= $message ?></div>
   <?php endif; ?>
   <?php if (!app()->auth::check()): ?>
      <form method="post">
          <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>

          <div class="form-group">
             <label>Логин</label>
             <input type="text" name="login" placeholder="Введите логин">
          </div>

          <div class="form-group">
             <label>Пароль</label>
             <input type="password" name="password" placeholder="Введите пароль">
          </div>

          <button class="btn btn-primary">войти</button>
      </form>
   <?php endif; ?>
</div>
