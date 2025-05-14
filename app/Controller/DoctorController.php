<?php

namespace Controller;

use Model\Doctor;
use Src\Request;
use Src\View;

class DoctorController
{
    public function doctor(Request $request): string
    {
        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                Doctor::deleteDoctor($request->get('delete_id'));
            } elseif ($request->has('last_name') && $request->has('first_name') && $request->has('specialization')) {
                Doctor::addDoctor($request->all());
            }
            // После добавления или удаления можно сделать редирект или обновить список
            app()->route->redirect('/doctor');
        }

        $doctors = Doctor::all();
        return (new View())->render('site.doctor', ['doctors' => $doctors]);
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

