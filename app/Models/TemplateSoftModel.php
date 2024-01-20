<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSoftModel extends Model
{
    use HasFactory;
    protected $table = "software_template";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'name'
    ];
}
