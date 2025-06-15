<?php

namespace Controller\Api;

use Model\Doctor;
use Src\Request;
use Src\View;

class DoctorController
{
    // Получить список всех врачей
    public function index(): void
    {
        $doctors = Doctor::all()->toArray();
        (new View())->toJSON($doctors);
    }

    // Можно добавить методы создания, редактирования, удаления врачей при необходимости
}
