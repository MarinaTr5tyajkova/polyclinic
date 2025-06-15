<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;
use DateTime;

class User extends Model implements IdentityInterface
{
    public $timestamps = false;
    protected $fillable = ['login', 'password', 'name', 'role'];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (strlen($user->password) !== 32 || !ctype_xdigit($user->password)) {
                $user->password = self::hashPassword($user->password);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                if (strlen($user->password) !== 32 || !ctype_xdigit($user->password)) {
                    $user->password = self::hashPassword($user->password);
                }
            }
        });
    }

    public static function hashPassword($password): string
    {
        return md5($password);
    }

    public function findIdentity(int $id): ?self
    {
        return self::find($id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function attemptIdentity(array $credentials): ?IdentityInterface
    {
        if (empty($credentials['login']) || empty($credentials['password'])) {
            error_log('Empty login or password');
            return null;
        }

        $user = self::where('login', $credentials['login'])->first();

        if (!$user) {
            error_log('User not found: ' . $credentials['login']);
            return null;
        }

        $hashedInputPassword = self::hashPassword($credentials['password']);
        error_log("Login: {$credentials['login']}, Input hash: {$hashedInputPassword}, DB hash: {$user->password}");

        if ($user->password === $hashedInputPassword) {
            return $user;
        }

        error_log('Password mismatch for user: ' . $credentials['login']);
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function roleName(): string
    {
        return $this->role ? ucfirst($this->role) : 'Unknown';
    }

    // Связь с моделью Employee
    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    /**
     * Генерирует уникальный токен для пользователя и сохраняет в базе
     * @return string
     * @throws \Exception
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(32)); // 64 символа
        $expiresAt = (new DateTime('+1 day'))->format('Y-m-d H:i:s');

        // Создаём запись в таблице api_tokens
        ApiToken::create([
            'user_id' => $this->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return $token;
    }

    /**
     * Связь с токенами API
     */
    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class, 'user_id', 'id');
    }
}
