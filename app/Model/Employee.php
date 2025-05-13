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
    protected $fillable = ['last_name', 'first_name', 'patronym', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function addEmployee($request)
    {
        if ($request->method === 'POST') {
            try {
                // Проверяем, существует ли пользователь с таким логином
                $existingUser = User::where('login', $request->get('login'))->first();
                if ($existingUser) {
                    throw new \Exception("Логин уже занят");
                }

                // Создаем пользователя
                $user = User::create([
                    'login' => $request->get('login'),
                    'password' => password_hash($request->get('password'), PASSWORD_DEFAULT),
                    'role' => 'employee'
                ]);

                // Проверяем, что пользователь создан
                if (!$user) {
                    throw new \Exception("Ошибка при создании пользователя");
                }

                // Создаем сотрудника
                $employee = self::create([
                    'last_name' => $request->get('last_name'),
                    'first_name' => $request->get('first_name'),
                    'patronym' => $request->get('patronym'),
                    'created_by' => Auth::user()->id,
                    'user_id' => $user->id
                ]);

                return $employee;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }
        return null;
    }

    public static function getEmployeesWithUser()
    {
        return self::with('user')->get();
    }

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