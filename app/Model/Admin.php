<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $fillable = ['user_id', 'last_name', 'first_name', 'patronym'];
}