<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabAttsModel extends Model
{
    use HasFactory;
    protected $table = "lab_attributes_values";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'lab_equipment',
        'attribute',
        'value'
    ];
}
