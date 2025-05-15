<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\Auth;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    // Добавляем avatar в fillable, чтобы можно было массово присваивать
    protected $fillable = [
        'last_name',
        'first_name',
        'patronym',
        'created_by',
        'user_id',
        'avatar',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Метод добавления сотрудника с аватаром
     * @param $request - объект запроса
     * @param string|null $avatarFileName - имя файла аватара
     * @return Employee|null
     */
    public static function addEmployee($request, $avatarFileName = null)
    {
        if ($request->method === 'POST') {
            // Проверяем, есть ли пользователь с таким логином
            $existingUser = User::where('login', $request->get('login'))->first();
            if ($existingUser) {
                return null; // Логин занят
            }

            // Создаём пользователя (пароль хешируется в модели User)
            $user = User::create([
                'login' => $request->get('login'),
                'password' => $request->get('password'),
                'role' => 'employee',
            ]);

            if (!$user) {
                return null;
            }

            // Создаём сотрудника с привязкой к пользователю и аватаром
            $employee = self::create([
                'last_name' => $request->get('last_name'),
                'first_name' => $request->get('first_name'),
                'patronym' => $request->get('patronym'),
                'created_by' => Auth::user()->id,
                'user_id' => $user->id,
                'avatar' => $avatarFileName,
            ]);

            return $employee;
        }
        return null;
    }

    /**
     * Метод для получения пути к аватару сотрудника
     * Если аватар не загружен или файл отсутствует - возвращает путь к дефолтному изображению
     * @return string
     */
    public function getAvatarPath()
    {
        $avatarDir = __DIR__ . '/../../public/uploads/avatars/';
        if ($this->avatar && file_exists($avatarDir . $this->avatar)) {
            return '/polyclinic/public/uploads/avatars/' . $this->avatar;
        }
        return '/polyclinic/public/assets/images/default-avatar.svg';
    }

    public static function syncEmployees()
    {
        // Получаем всех пользователей с ролью employee
        $users = User::where('role', 'employee')->get();

        foreach ($users as $user) {
            // Проверяем, существует ли запись в таблице employee
            $employee = self::where('user_id', $user->id)->first();
            if (!$employee) {
                // Создаем запись в таблице employee
                self::create([
                    'last_name' => 'Не указано',
                    'first_name' => 'Не указано',
                    'patronym' => 'Не указано',
                    'created_by' => 1, // ID администратора
                    'user_id' => $user->id
                ]);
            }
        }
    }

    // Метод для получения сотрудников с данными пользователей
    public static function getEmployeesWithUser()
    {
        return self::with('user')->get();
    }

    // Метод для удаления сотрудника
    public static function deleteEmployee($id)
    {
        $employee = self::find($id);
        if ($employee) {
            $employee->user()->delete();
            return $employee->delete();
        }
        return false;
    }

}
