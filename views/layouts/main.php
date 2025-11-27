<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Pop it MVC</title>
   <link rel="stylesheet" href="<?= app()->route->getUrl('/css/main.css') ?>">
</head>
<body>
<div class="container">
   <header>
       <nav>
           <?php
           use Src\Auth\Auth;
           if (!Auth::check()):
               ?>
               <a href="<?= app()->route->getUrl('/hello') ?>">Главная</a>
               <a href="<?= app()->route->getUrl('/login') ?>">Вход</a>
               <a href="<?= app()->route->getUrl('/signup') ?>">Регистрация пациента</a>
           <?php
           else:
               $user = Auth::user();
               ?>
                <a href="<?= app()->route->getUrl('/') ?>">Дашборд</a>
                <a href="<?= app()->route->getUrl('/appointments') ?>">Расписание</a>
                <?php if ($user->role === 'Администратор'): ?>
                    <a href="<?= app()->route->getUrl('/users') ?>">Пользователи</a>
                <?php endif; ?>
               <div class="user-info">
                   Добро пожаловать, <?= $user->name ?>! 
                   <span class="user-role">(<?= $user->role ?>)</span>
               </div>
               <a href="<?= app()->route->getUrl('/logout') ?>">Выход</a>
           <?php
           endif;
           ?>
       </nav>
   </header>
   <main>
       <?= $content ?? '' ?>
   </main>
</div>

</body>
</html>
