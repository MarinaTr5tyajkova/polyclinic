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
    protected $fillable = ['last_name', 'first_name', 'patronym', 'created_by', 'user_id'];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Метод добавления сотрудника
    public static function addEmployee($request)
    {
        if ($request->method === 'POST') {
            // Проверяем, существует ли пользователь с таким логином
            $existingUser = User::where('login', $request->get('login'))->first();
            if ($existingUser) {
                return null; // Логин уже занят
            }

            // Создаём пользователя с НЕ захешированным паролем (хешируется в модели User)
            $user = User::create([
                'login' => $request->get('login'),
                'password' => $request->get('password'),
                'role' => 'employee'
            ]);

            if (!$user) {
                return null; // Ошибка создания пользователя
            }

            // Создаём сотрудника, связанного с пользователем
            $employee = self::create([
                'last_name' => $request->get('last_name'),
                'first_name' => $request->get('first_name'),
                'patronym' => $request->get('patronym'),
                'created_by' => Auth::user()->id,
                'user_id' => $user->id
            ]);

            return $employee;
        }
        return null;
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
