<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftVersTemplateSoftModel extends Model
{
    use HasFactory;
    protected $table = "soft_vers_template";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'soft_vers',
        'soft_template'
    ];
}
