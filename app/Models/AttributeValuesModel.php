<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValuesModel extends Model
{
    use HasFactory;

    protected $table = "attribute_list_values";

    public $timestamps = false;

    protected $fillable=[
        'id',
        'attribute',
        'value'
    ];
}
