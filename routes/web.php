<?php

use Controller\AppointmentController;
use Controller\DashboardController;
use Controller\Site;
use Controller\UserController;
use Src\Route;

Route::add('GET', '/', [DashboardController::class, 'index'])
   ->middleware('auth');
Route::add('GET', '/hello', [Site::class, 'hello']);
Route::add(['GET', 'POST'], '/signup', [Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Site::class, 'login']);
Route::add('GET', '/logout', [Site::class, 'logout'])
   ->middleware('auth');

Route::add('GET', '/users', [UserController::class, 'index'])
   ->middleware('auth', 'role:Администратор');
Route::add(['GET', 'POST'], '/users/create', [UserController::class, 'create'])
   ->middleware('auth', 'role:Администратор');

// Маршруты для записей на прием
Route::add('GET', '/appointments', [AppointmentController::class, 'index'])
   ->middleware('auth');
Route::add(['GET', 'POST'], '/appointments/create', [AppointmentController::class, 'create'])
   ->middleware('auth', 'role:Пациент,Администратор');
Route::add(['GET', 'POST'], '/appointments/manage', [AppointmentController::class, 'manage'])
   ->middleware('auth', 'role:Врач,Администратор');
Route::add('GET', '/appointments/delete', [AppointmentController::class, 'delete'])
   ->middleware('auth', 'role:Администратор');