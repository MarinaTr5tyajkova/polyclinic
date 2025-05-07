<?php

namespace Controller;

use Model\Doctor;
use Src\View;

class DoctorController
{
    public function doctor(): string
    {
        $doctors = Doctor::all(); // Исправлено: было $patients, стало $doctors
        return (new View())->render('site.doctor', ['doctors' => $doctors]);
    }
}