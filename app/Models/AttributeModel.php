<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeModel extends Model
{
    use HasFactory;
    protected $table = "equipments_attributes";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'equipment',
        'name',
        'type'
    ];
}
