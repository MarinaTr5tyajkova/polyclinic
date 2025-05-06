<?php
use Src\Route;

// Главная страница
Route::add('patient', [Controller\PatientController::class, 'patient']);
Route::add('doctor', [Controller\DoctorController::class, 'doctor']);
Route::add('record', [Controller\RecordController::class, 'record']);

