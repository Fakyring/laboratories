<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Model
{
    public $timestamps = false;
    protected $table = "users";

    protected $fillable=[
        'id',
        'password',
        'email',
        'surname',
        'name',
        'patronymic',
        'role',
        'enabled'
    ];
}
