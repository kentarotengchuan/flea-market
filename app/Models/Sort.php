<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sort extends Model
{
    protected $fillable = [
        'product_id',
        'category_id',
    ];
}