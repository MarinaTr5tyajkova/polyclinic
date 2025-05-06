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
        'birthday'
    ];
}