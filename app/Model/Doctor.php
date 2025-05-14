<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Метод для добавления врача
    public static function addDoctor(array $data)
    {
        return self::create([
            'last_name' => $data['last_name'] ?? '',
            'first_name' => $data['first_name'] ?? '',
            'patronym' => $data['patronym'] ?? '',
            'specialization' => $data['specialization'] ?? '',
            'birthday' => $data['birthday'] ?? null,
            'post' => $data['post'] ?? '',
        ]);
    }

    // Метод для удаления врача по ID
    public static function deleteDoctor($id)
    {
        $doctor = self::find($id);
        if ($doctor) {
            return $doctor->delete();
        }
        return false;
    }

    // Поиск врачей по запросу (фамилия, имя, отчество, специализация, должность)
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
