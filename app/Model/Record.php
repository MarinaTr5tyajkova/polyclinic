<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'record';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'employee_id',
        'patient_id',
        'doctor_id',
        'record_date',
        'record_time',
    ];

    // Отношения
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * Поиск записей с фильтрами
     * @param array $filters ['doctor' => '', 'patient' => '', 'date' => '']
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function search(array $filters = [])
    {
        $query = self::with(['patient', 'doctor']);

        if (!empty($filters['doctor'])) {
            $doctorSearch = $filters['doctor'];
            $query->whereHas('doctor', function ($q) use ($doctorSearch) {
                $q->whereRaw("CONCAT(last_name, ' ', first_name, ' ', patronym) LIKE ?", ["%{$doctorSearch}%"]);
            });
        }

        if (!empty($filters['patient'])) {
            $patientSearch = $filters['patient'];
            $query->whereHas('patient', function ($q) use ($patientSearch) {
                $q->whereRaw("CONCAT(last_name, ' ', first_name, ' ', patronym) LIKE ?", ["%{$patientSearch}%"]);
            });
        }

        if (!empty($filters['date'])) {
            $query->where('record_date', $filters['date']);
        }

        return $query->get();
    }

    public static function deleteRecord($id)
    {
        $record = self::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public static function toggleStatus($id)
    {
        $record = self::find($id);
        if (!$record) {
            return false;
        }

        // Предположим, два статуса: 'complete' и 'canceled'
        $record->status = ($record->status === 'complete') ? 'canceled' : 'complete';
        return $record->save();
    }
}
