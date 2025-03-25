<?php

namespace App\Models\Policies;

use App\Models\MultiTable\RegionsModel;
use App\Models\MultiTable\StatesModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CountiesModel extends Model
{
    //
    protected $table = "counties";

    protected $fillable = [
        "name",
        "description",
        "sort_order",
        "status",
        "fk_state",
        "fk_region",
    ];

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Inactive", 1 => "Active"][$this->status]
        );
    }
    
    public function state(){
        return $this->belongsTo(StatesModel::class,"fk_state","id");
    }
    
    public function region(){
        return $this->belongsTo(RegionsModel::class,"fk_region","id");
    }
}
