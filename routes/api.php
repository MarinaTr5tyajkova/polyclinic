<?php

use Src\Route;

// Все маршруты без префикса /api — он добавится группой
Route::add('GET', '/patients', [Controller\Api\PatientController::class, 'index']);
Route::add('POST', '/patients', [Controller\Api\PatientController::class, 'store']);
Route::add('GET', '/patients/{id}', [Controller\Api\PatientController::class, 'show']);

Route::add('GET', '/doctors', [Controller\Api\DoctorController::class, 'index']);

Route::add('POST', '/register', [Controller\Api\AuthController::class, 'register']);
Route::add('POST', '/login', [Controller\Api\AuthController::class, 'login']);
Route::add('GET', '/secure-data', [Controller\Api\AuthController::class, 'secureData']);

Route::add('GET', '/profile', [Controller\UserController::class, 'getProfile'])
    ->middleware('bearer'); // middleware для проверки Bearer токена

