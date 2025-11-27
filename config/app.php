<?php
return [
   //Класс аутентификации
   'auth' => \Src\Auth\Auth::class,
   //Класс пользователя
   'identity' => \Model\User::class,
   //Классы для middleware маршрутов
   'routeMiddleware' => [
      'auth' => \Middlewares\AuthMiddleware::class,
      'role' => \Middlewares\RoleMiddleware::class,
   ],
   //Валидаторы
   'validators' => [
      'required' => \Src\Validator\RequireValidator::class,
      'unique' => \Src\Validator\UniqueValidator::class,
      'phone' => \Src\Validator\PhoneValidator::class,
      'date_after' => \Src\Validator\DateAfterValidator::class,
      'date_before' => \Src\Validator\DateBeforeValidator::class,
      'enum' => \Src\Validator\EnumValidator::class,
      'image' => \Src\Validator\ImageValidator::class,
   ],
   //Глобальные middleware приложения
   'routeAppMiddleware' => [
      'csrf' => \Middlewares\CSRFMiddleware::class,
      'trim' => \Middlewares\TrimMiddleware::class,
      'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
   ],
];
