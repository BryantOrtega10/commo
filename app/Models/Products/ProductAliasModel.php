<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class ProductAliasModel extends Model
{
    protected $table = "product_alias";

    protected $fillable = [
        "alias",
        "fk_product"
    ];
}
