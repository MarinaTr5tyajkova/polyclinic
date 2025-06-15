<?php
use Src\Route;

// Главная страница
Route::add('go', [Controller\Site::class, 'index']);

// Страница "Hello"
Route::add('hello', [Controller\Site::class, 'hello']);
