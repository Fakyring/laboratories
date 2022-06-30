<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSoftModel extends Model
{
    use HasFactory;
    protected $table = "labs_software";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'laboratory',
        'soft_ver'
    ];
}
