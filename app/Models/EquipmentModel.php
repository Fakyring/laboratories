<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentModel extends Model
{
    use HasFactory;
    protected $table = "equipments";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'name',
        'enabled'
    ];
}
