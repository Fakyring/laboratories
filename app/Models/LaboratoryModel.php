<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryModel extends Model
{
    use HasFactory;
    protected $table = "laboratories";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'name',
        'sub-type',
        'descr',
        'image',
        'enabled'
    ];
}
