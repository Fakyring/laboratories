<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabEqModel extends Model
{
    use HasFactory;
    protected $table = "labs_equipments";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'laboratory',
        'equipment',
        'amount',
        'teacher'
    ];
}
