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
                $deleted = Patient::deleteById($request->get('delete_id'));
                $message = $deleted ? 'Пациент удалён' : 'Пациент не найден';

                // После удаления можно сделать редирект, чтобы избежать повторного удаления при обновлении
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $form_data = $request->all();

                $result = Patient::createWithValidation($form_data);

                if ($result['success']) {
                    // После успешного добавления делаем редирект, чтобы избежать повторного добавления при обновлении страницы
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    $errors = $result['errors'];
                    $form_data = $request->all(); // сохранить введённые данные для отображения формы
                }
            }
        }

        $patients = Patient::getAll();

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
