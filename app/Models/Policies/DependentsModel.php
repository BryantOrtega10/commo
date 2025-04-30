<?php

namespace App\Models\Policies;

use App\Models\MultiTable\RelationshipsModel;
use Illuminate\Database\Eloquent\Model;

class DependentsModel extends Model
{
    //

    protected $table = "dependents";

    protected $fillable = [
        "first_name",
        "last_name",
        "date_birth",
        "fk_relationship",
        "date_added",
        "fk_policy",
    ];
    
    public function relationship(){
        return $this->belongsTo(RelationshipsModel::class, "fk_relationship", "id");
    }

    public function policy(){
        return $this->belongsTo(PoliciesModel::class, "fk_policy", "id");
    }
    
}
