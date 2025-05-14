<?php

namespace Controller;

use Model\Patient;
use Src\Request;
use Src\View;

class PatientController
{
    public function patient(Request $request): string
    {
        // Обработка POST-запросов для добавления или удаления
        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                Patient::deletePatient($request->get('delete_id'));
            } elseif ($request->has('last_name') && $request->has('first_name') && $request->has('birthday')) {
                Patient::addPatient($request->all());
            }
        }

        // Получаем список пациентов
        $patients = Patient::all();

        // Рендерим страницу с пациентами
        return (new View())->render('site.patient', [
            'patients' => $patients,
        ]);
    }

    public function search(Request $request): string
    {
        $searchQuery = $request->get('search_query', '');

        $patients = Patient::searchByFullName($searchQuery);

        return (new View())->render('site.patient', [
            'patients' => $patients,
            'search_query' => $searchQuery
        ]);
    }
}