<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Validator\Validator;

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

    /**
     * Добавление пациента с валидацией
     * @param array $data
     * @return array ['success' => bool, 'errors' => array|null, 'patient' => Patient|null]
     */
    public static function addPatientWithValidation(array $data): array
    {
        $validator = new Validator($data, [
            'last_name' => ['required'],
            'first_name' => ['required'],
            'birthday' => ['required', 'age:18'], // обязательное поле и проверка возраста от 18 лет
        ], [
            'required' => 'Поле :field обязательно для заполнения',
            'age' => 'Возраст должен быть не менее 18 лет',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'patient' => null,
            ];
        }

        $patient = self::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'patronym' => $data['patronym'] ?? '',
            'birthday' => $data['birthday'],
        ]);

        return [
            'success' => true,
            'errors' => null,
            'patient' => $patient,
        ];
    }

    public static function deletePatient($id): bool
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
