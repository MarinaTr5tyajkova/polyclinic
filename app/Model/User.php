<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    public $timestamps = false;
    protected $fillable = ['login', 'password', 'name', 'role'];

    protected static function booted()
    {
        static::creating(function ($user) {
            // Хешируем пароль при создании
            $user->password = self::hashPassword($user->password);
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
            return null;
        }

        // Ищем пользователя по логину
        $user = self::where('login', $credentials['login'])->first();

        // Проверяем, совпадает ли MD5-хэш пароля
        if ($user && $user->password === md5($credentials['password'])) {
            return $user;
        }

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
}