<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwaresVersionsModel extends Model
{
    use HasFactory;
    protected $table = "softwares_versions";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'software',
        'version'
    ];
}
