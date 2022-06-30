<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabRespModel extends Model
{
    use HasFactory;
    protected $table = "labs_responsibles";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'laboratory',
        'responsible'
    ];
}
