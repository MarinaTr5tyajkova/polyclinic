<?php
namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';
    public $timestamps = false;

    // Указываем правильное имя первичного ключа
    protected $primaryKey = 'user_id';

    // Разрешаем массовое заполнение полей
    protected $fillable = ['last_name', 'first_name', 'patronym', 'created_by'];

    /**
     * Отношение "Сотрудник принадлежит пользователю".
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Получить всех сотрудников с данными пользователя.
     */
    public function getEmployeesWithUser()
    {
        return $this->with('user')->get();
    }

    /**
     * Добавить нового сотрудника.
     *
     * @param array $data
     * @return \Model\Employee
     */
    public function addEmployee(array $data)
    {
        // Создаем пользователя в таблице users
        $userData = [
            'login' => $data['login'],
            'password' => md5($data['password']), // Хэшируем пароль через MD5
            'role' => 'employee',
        ];

        $user = (new User())->create($userData);

        // Создаем сотрудника в таблице employee
        $employeeData = [
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'patronym' => $data['patronym'] ?? null,
            'created_by' => $data['admin_id'] ?? null,
            'user_id' => $user->id,
        ];

        return $this->create($employeeData);
    }

    /**
     * Удалить сотрудника по ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEmployee(int $id): bool
    {
        $employee = $this->where('user_id', $id)->first(); // Ищем сотрудника по user_id
        if ($employee) {
            // Удаляем связанного пользователя
            $employee->user()->delete();
            return $employee->delete(); // Удаляем сотрудника
        }
        return false;
    }
}