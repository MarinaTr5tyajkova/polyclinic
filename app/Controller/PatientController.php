<?php

namespace Controller;

use Model\Patient;
use Src\View;

class PatientController
{
    public function patient(): string
    {
        $patients = Patient::all();
        return (new View())->render('site.patient', ['patients' => $patients]);
    }
}