<?php

namespace Controller\Api;

use Model\Patient;
use Src\Request;
use Src\View;

class PatientController
{
    // Получить список всех пациентов
    public function index(): void
    {
        $patients = Patient::all()->toArray();
        (new View())->toJSON($patients);
    }

    // Создать нового пациента
    public function store(Request $request): void
    {
        $data = $request->all();

        // Здесь можно добавить валидацию данных

        $patient = Patient::create($data);

        if ($patient) {
            (new View())->toJSON($patient->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Не удалось создать пациента'], 500);
        }
    }

    // Получить данные конкретного пациента по ID
    public function show(Request $request, $id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            (new \Src\View())->toJSON($patient->toArray());
        } else {
            (new \Src\View())->toJSON(['error' => 'Пациент не найден'], 404);
        }
    }

}
