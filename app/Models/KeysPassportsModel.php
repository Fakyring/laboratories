<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeysPassportsModel extends Model
{
    use HasFactory;
    protected $table = "keys_passports";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'key',
        'value',
        'passport'
    ];
}
