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
                $deleted = Doctor::deleteById($request->get('delete_id'));
                $message = $deleted ? 'Врач удалён' : 'Врач не найден';

                // Редирект после удаления, чтобы избежать повторного удаления при обновлении страницы
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $form_data = $request->all();

                $result = Doctor::createWithValidation($form_data);

                if ($result['success']) {
                    // Редирект после успешного добавления, чтобы избежать повторного добавления при обновлении страницы
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    $errors = $result['errors'];
                    // Сохраняем данные формы для повторного отображения
                    $form_data = $request->all();
                }
            }
        }

        $doctors = Doctor::getAll();

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
