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
     * Очистка пробелов у всех строковых полей
     */
    protected static function trimData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }
        return $data;
    }

    /**
     * Создание пациента с валидацией и очисткой
     * @param array $data
     * @return array ['success' => bool, 'errors' => array|null, 'patient' => Patient|null]
     */
    public static function createWithValidation(array $data): array
    {
        $data = self::trimData($data);

        $validator = new Validator($data, [
            'last_name' => ['required'],
            'first_name' => ['required'],
            'birthday' => ['required', 'age:18'],
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

    /**
     * Удаление пациента по ID
     * @param int $id
     * @return bool
     */
    public static function deleteById($id): bool
    {
        $patient = self::find($id);
        if ($patient) {
            return $patient->delete();
        }
        return false;
    }

    /**
     * Получить всех пациентов
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        return self::all();
    }

    /**
     * Поиск пациентов по ФИО с поддержкой нескольких слов
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
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
