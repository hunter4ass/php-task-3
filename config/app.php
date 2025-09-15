<?php
return [
   //Класс аутентификации
   'auth' => \Src\Auth\Auth::class,
   //Класс пользователя
   'identity' => \Model\User::class,
   //Классы для middleware маршрутов
   'routeMiddleware' => [
      'auth' => \Middlewares\AuthMiddleware::class,
   ],
   //Валидаторы
   'validators' => [
      'required' => \Src\Validator\RequireValidator::class,
      'unique' => \Src\Validator\UniqueValidator::class,
   ],
   //Глобальные middleware приложения
   'routeAppMiddleware' => [
      'trim' => \Middlewares\TrimMiddleware::class,
   ],
   'routeAppMiddleware' => [
   'trim' => \Middlewares\TrimMiddleware::class,
   'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
],
'routeAppMiddleware' => [
   'csrf' => \Middlewares\CSRFMiddleware::class,
   'trim' => \Middlewares\TrimMiddleware::class,
   'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
],


];
