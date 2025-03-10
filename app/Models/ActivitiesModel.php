<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ActivitiesModel extends Model
{
    //
    protected $table = "activities";

    protected $fillable = [
        "type",
        "description",
        "task_name",
        "expiration_date",
        "priority",
        "fk_customer",
    ];

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Inactive", 1 => "Active"][$this->status]
        );
    }

    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer", "id");
    }
}
