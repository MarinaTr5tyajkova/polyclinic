<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Validator\Validator;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctor';
    public $timestamps = false;

    protected $fillable = [
        'last_name',
        'first_name',
        'patronym',
        'specialization',
        'birthday',
        'post'
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
     * Создание врача с валидацией и очисткой данных
     * @param array $data
     * @return array ['success' => bool, 'errors' => array|null, 'doctor' => Doctor|null]
     */
    public static function createWithValidation(array $data): array
    {
        // Очистка пробелов (если middleware не сработал, на всякий случай)
        $data = self::trimData($data);

        $validator = new Validator($data, [
            'last_name' => ['required'],
            'first_name' => ['required'],
            'specialization' => ['required'],
            'birthday' => ['required', 'age:18'],
            'post' => ['required'],
        ], [
            'required' => 'Поле :field обязательно для заполнения',
            'age' => 'Возраст должен быть не менее 18 лет',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'doctor' => null,
            ];
        }

        $doctor = self::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'patronym' => $data['patronym'] ?? '',
            'specialization' => $data['specialization'],
            'birthday' => $data['birthday'],
            'post' => $data['post'],
        ]);

        return [
            'success' => true,
            'errors' => null,
            'doctor' => $doctor,
        ];
    }

    /**
     * Удаление врача по ID
     * @param int $id
     * @return bool
     */
    public static function deleteById($id): bool
    {
        $doctor = self::find($id);
        if ($doctor) {
            return $doctor->delete();
        }
        return false;
    }

    /**
     * Получить всех врачей
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        return self::all();
    }

    /**
     * Поиск врачей по запросу (фамилия, имя, отчество, специализация, должность)
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function searchByQuery(string $query)
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
                    ->orWhere('patronym', 'like', "%{$word}%")
                    ->orWhere('specialization', 'like', "%{$word}%")
                    ->orWhere('post', 'like', "%{$word}%");
            }
        })->get();
    }
}
