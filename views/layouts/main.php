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
           <a href="<?= app()->route->getUrl('/') ?>">Главная</a>
           <?php
           if (!app()->auth::check()):
               ?>
               <a href="<?= app()->route->getUrl('/login') ?>">Вход</a>
               <a href="<?= app()->route->getUrl('/signup') ?>">Регистрация</a>
           <?php
           else:
               ?>
               <div class="user-info">Добро пожаловать, <?= app()->auth::user()->name ?>!</div>
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
