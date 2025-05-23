<?php

namespace Controller;

use Model\Record;
use Model\Doctor;
use Model\Patient;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class RecordController
{
    public function record(Request $request): string
    {
        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                Record::deleteRecord($request->get('delete_id'));
            } elseif ($request->has('toggle_status_id')) {
                Record::toggleStatus($request->get('toggle_status_id'));
            }
        }

        // Получение и фильтрация записей для GET-запроса
        $filters = [
            'doctor' => $request->get('doctor'),
            'patient' => $request->get('patient'),
            'date' => $request->get('date'),
        ];

        $records = Record::search($filters);

        return (new View())->render('site.record', [
            'records' => $records,
            'doctorSearch' => $filters['doctor'],
            'patientSearch' => $filters['patient'],
            'dateSearch' => $filters['date'],
        ]);
    }



    // Форма создания записи (GET и POST)
    public function create(Request $request): string
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $message = null;
        $form_data = [];

        if ($request->method === 'POST') {
            $form_data = $request->all();

            try {
                $record = Record::create([
                    'employee_id' => Auth::user()->id,
                    'doctor_id' => $request->get('doctor_id'),
                    'patient_id' => $request->get('patient_id'),
                    'record_date' => $request->get('record_date'),
                    'record_time' => $request->get('record_time'),
                    'status' => $request->get('status') ?? 'complete',
                ]);

                if ($record) {
                    header('Location: /polyclinic/record');
                    exit();
                }
            } catch (\Exception $e) {
                $message = 'Ошибка при создании записи: ' . $e->getMessage();
            }
        }

        return (new View())->render('site.record_create', [
            'doctors' => $doctors,
            'patients' => $patients,
            'message' => $message,
            'form_data' => $form_data,
        ]);
    }
}