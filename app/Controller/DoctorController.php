<?php

namespace Controller;

use Model\Doctor;
use Src\Request;
use Src\View;

class DoctorController
{
    public function doctor(Request $request): string
    {
        $message = null;
        $errors = null;
        $form_data = [];

        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                Doctor::deleteDoctor($request->get('delete_id'));
                $message = 'Врач удалён';
            } elseif ($request->has('last_name') && $request->has('first_name') && $request->has('specialization')) {
                $form_data = $request->all();
                $result = Doctor::addDoctorWithValidation($form_data);

                if ($result['success']) {
                    $message = 'Врач успешно добавлен';
                    $form_data = []; // очистить форму после успешного добавления
                } else {
                    $errors = $result['errors'];
                }
            }
            // Можно убрать редирект, чтобы показывать ошибки и форму с сохранёнными данными
            // app()->route->redirect('/doctor');
        }

        $doctors = Doctor::all();

        return (new View())->render('site.doctor', [
            'doctors' => $doctors,
            'message' => $message,
            'errors' => $errors,
            'form_data' => $form_data,
        ]);
    }

    public function search(Request $request): string
    {
        $query = $request->get('search_query', '');
        $doctors = Doctor::searchByQuery($query);
        return (new View())->render('site.doctor', [
            'doctors' => $doctors,
            'search_query' => $query
        ]);
    }
}
