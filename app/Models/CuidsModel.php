<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CuidsModel extends Model
{
    //
    protected $table = "cuids";

    protected $fillable = [
        "name",
        "fk_carrier",
        "fk_business_segment",
        "validation_date",
        "validation_note",
        "fk_customer",
    ];

    public function carrier(){
        return $this->belongsTo(CarriersModel::class, "fk_carrier", "id");
    }

    public function business_segment(){
        return $this->belongsTo(BusinessSegmentsModel::class, "fk_business_segment", "id");
    }
    
    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer", "id");
    }

}
