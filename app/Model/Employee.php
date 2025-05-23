<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\Auth;
use Model\User;
use Src\Validator\Validator;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'last_name',
        'first_name',
        'patronym',
        'created_by',
        'user_id',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Очищает пробелы у всех строковых полей
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
     * Создаёт сотрудника с валидацией, загрузкой аватара и созданием пользователя
     * @param array $data
     * @param array|null $file
     * @return array ['success' => bool, 'errors' => array|null, 'employee' => Employee|null]
     */
    public static function createEmployee(array $data, ?array $file = null): array
    {
        $data = self::trimData($data);

        $validator = new Validator($data, [
            'last_name' => ['required'],
            'first_name' => ['required'],
            'login' => ['login', 'unique:users,login', 'required'],
            'password' => ['required', 'password'],
        ], [
            'required' => 'Поле :field обязательно для заполнения',
            'unique' => 'Поле :field должно быть уникальным',
            'password' => 'Пароль должен содержать минимум 6 символов, одну заглавную букву и одну цифру',
            'login' => 'Логин может содержать только латинские буквы, цифры, символы "_" и "-", и не должно содержать кириллицу',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'employee' => null
            ];
        }

        $avatarFileName = self::uploadAvatar($file);

        try {
            $user = User::create([
                'login' => $data['login'],
                'password' => $data['password'], // модель User должна сама хешировать пароль
                'role' => 'employee',
            ]);

            if (!$user) {
                throw new \Exception('Не удалось создать пользователя');
            }

            $employee = self::create([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'patronym' => $data['patronym'] ?? null,
                'created_by' => Auth::user()->id,
                'user_id' => $user->id,
                'avatar' => $avatarFileName,
            ]);

            return ['success' => true, 'errors' => null, 'employee' => $employee];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['database' => ['Ошибка при сохранении: ' . $e->getMessage()]], 'employee' => null];
        }
    }

    /**
     * Загрузка аватара, возвращает имя файла или дефолт
     * @param array|null $file
     * @return string
     */
    public static function uploadAvatar(?array $file): string
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return 'default-avatar.svg';
        }

        $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('avatar_') . '.' . $extension;

        move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);

        return $fileName;
    }

    /**
     * Получить путь к аватару
     * @return string
     */
    public function getAvatarPath(): string
    {
        $avatarDir = __DIR__ . '/../../public/uploads/avatars/';
        if ($this->avatar && file_exists($avatarDir . $this->avatar)) {
            return '/polyclinic/public/uploads/avatars/' . $this->avatar;
        }
        return '/polyclinic/public/assets/images/default-avatar.svg';
    }

    /**
     * Получить всех сотрудников с пользователями
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEmployeesWithUser()
    {
        return self::with('user')->get();
    }

    /**
     * Удалить сотрудника и связанного пользователя
     * @param int $id
     * @return bool
     */
    public static function deleteEmployee($id): bool
    {
        $employee = self::find($id);
        if ($employee) {
            $employee->user()->delete();
            return $employee->delete();
        }
        return false;
    }
}
