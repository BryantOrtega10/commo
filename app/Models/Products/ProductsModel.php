<?php

namespace App\Models\Products;

use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\TiersModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    //

    protected $table = "products";

    protected $fillable = [
        "description",
        "fk_carrier",
        "fk_business_segment",
        "fk_business_type",
        "fk_product_type",
        "fk_plan_type",
        "fk_tier",
        "fk_entry_user",
    ];


    public function carrier(){
        return $this->belongsTo(CarriersModel::class, "fk_carrier","id");
    }
    
    public function business_segment(){
        return $this->belongsTo(BusinessSegmentsModel::class, "fk_business_segment","id");
    }
    
    public function business_type(){
        return $this->belongsTo(BusinessTypesModel::class, "fk_business_type","id");
    }
    
    public function product_type(){
        return $this->belongsTo(ProductTypesModel::class, "fk_product_type","id");
    }
    
    public function plan_type(){
        return $this->belongsTo(PlanTypesModel::class, "fk_plan_type","id");
    }
    
    public function tier(){
        return $this->belongsTo(TiersModel::class, "fk_tier","id");
    }
    
    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user","id");
    }
    
    public function product_alias() {
        return $this->hasMany(ProductAliasModel::class, "fk_product", "id");
    }
}
