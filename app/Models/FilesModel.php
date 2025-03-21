<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilesModel extends Model
{
    protected $table = "files";

    protected $fillable = [
        "name",
        "route",
        "fk_customer"
    ];

    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer", "id");
    }
}
