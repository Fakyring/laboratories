<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareModel extends Model
{
    use HasFactory;
    protected $table = "softwares";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'name'
    ];
}
