<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient';
    public $timestamps = false;

    protected $fillable = [
        'last_name',
        'first_name',
        'patronym',
        'birthday'
    ];

    // Метод для добавления пациента
    public static function addPatient($data)
    {
        return self::create([
            'last_name' => $data['last_name'] ?? '',
            'first_name' => $data['first_name'] ?? '',
            'patronym' => $data['patronym'] ?? '',
            'birthday' => $data['birthday'] ?? null,
        ]);
    }

    // Метод для удаления пациента по ID
    public static function deletePatient($id)
    {
        $patient = self::find($id);
        if ($patient) {
            return $patient->delete();
        }
        return false;
    }

    public static function searchByFullName(string $query)
    {
        $query = trim($query);
        if ($query === '') {
            return self::all();
        }

        $words = preg_split('/\s+/', $query);

        return self::where(function ($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('last_name', 'like', "%{$word}%")
                    ->orWhere('first_name', 'like', "%{$word}%")
                    ->orWhere('patronym', 'like', "%{$word}%");
            }
        })->get();
    }
}