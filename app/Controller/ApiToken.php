<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Связь с пользователем, которому принадлежит токен
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

