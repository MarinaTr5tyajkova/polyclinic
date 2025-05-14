<?php
use Src\Route;

// Маршруты аутентификации
Route::add(['GET', 'POST'], '/login', [Controller\UserController::class, 'login']); // Вход в систему
Route::add('GET', '/logout', [Controller\UserController::class, 'logout'])
    ->middleware('admin'); // Выход только для авторизованных

// Маршруты для администраторов
Route::add(['GET', 'POST'], '/admin/employees', [Controller\AdminController::class, 'employees'])
    ->middleware('admin'); // Управление сотрудниками регистратуры (только для администраторов)

// Маршруты для сотрудников
Route::add(['GET', 'POST'], '/patient', [Controller\PatientController::class, 'patient'])
    ->middleware('employee'); // Пациенты (только для сотрудников)
Route::add(['GET', 'POST'], '/record', [Controller\RecordController::class, 'record'])
    ->middleware('employee'); // Записи (только для сотрудников)
Route::add(['GET', 'POST'], '/record_create', [Controller\RecordController::class, 'create'])
    ->middleware('employee');
// Поиск пациентов (GET)
Route::add('GET', '/patient/search', [Controller\PatientController::class, 'search'])
    ->middleware('employee');

Route::add(['GET', 'POST'], '/doctor', [Controller\DoctorController::class, 'doctor'])
    ->middleware('employee'); // Страница врачей с добавлением и удалением

Route::add('GET', '/doctor/search', [Controller\DoctorController::class, 'search'])
    ->middleware('employee'); // Поиск врачей

