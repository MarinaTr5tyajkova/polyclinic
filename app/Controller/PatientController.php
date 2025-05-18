<?php

namespace Controller;

use Model\Patient;
use Src\Request;
use Src\View;

class PatientController
{
    public function patient(Request $request): string
    {
        $message = null;
        $errors = null;
        $form_data = [];

        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                Patient::deletePatient($request->get('delete_id'));
                $message = 'Пациент удалён';
            } elseif ($request->has('last_name') && $request->has('first_name') && $request->has('birthday')) {
                $form_data = $request->all();
                $result = Patient::addPatientWithValidation($form_data);

                if ($result['success']) {
                    $message = 'Пациент успешно добавлен';
                    $form_data = []; // очистить форму после успешного добавления
                } else {
                    $errors = $result['errors'];
                }
            }
        }

        $patients = Patient::all();

        return (new View())->render('site.patient', [
            'patients' => $patients,
            'message' => $message,
            'errors' => $errors,
            'form_data' => $form_data,
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
