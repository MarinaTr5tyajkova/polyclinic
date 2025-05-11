<?php
use Src\Route;

// Главная страница
Route::add('GET', '/patient', [Controller\PatientController::class, 'patient'])
    ->middleware('auth'); // Пример добавления middleware для защиты маршрута

Route::add('GET', '/doctor', [Controller\DoctorController::class, 'doctor'])
    ->middleware('auth'); // Пример добавления middleware для роли администратора

Route::add('GET', '/record', [Controller\RecordController::class, 'record'])
    ->middleware('auth'); // Пример добавления middleware для защиты маршрута

// Маршруты аутентификации
Route::add(['GET', 'POST'], '/login', [Controller\User::class, 'login']); // Поддержка GET и POST для входа
Route::add('GET', '/logout', [Controller\User::class, 'logout']); // Выход
