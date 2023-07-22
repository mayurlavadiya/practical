<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    use HasFactory;

    protected $table = 'product_masters';

    protected $primaryKey = 'Product_ID';

    protected $fillable = [
        'Product_Name',
        'Rate',
        'Unit',
    ];
}
