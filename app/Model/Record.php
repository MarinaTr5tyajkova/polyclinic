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
        'date',
        'time'
    ];

    // Отношения
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'UniquelD');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'UniquelD');
    }

    // Метод для получения записей с ФИО
    public static function getAllWithNames()
    {
        return self::with(['patient', 'doctor'])->get();
    }
}