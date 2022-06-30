<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeysModel extends Model
{
    use HasFactory;
    protected $table = "keys";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'action'
    ];
}
