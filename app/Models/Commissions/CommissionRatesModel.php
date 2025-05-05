<?php

namespace App\Models\Commissions;

use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RegionsModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Products\ProductsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CommissionRatesModel extends Model
{
    
    protected $table = "commission_rates";

    protected $fillable = [
        "fk_agent_number",
        "fk_business_segment",
        "fk_business_type",
        "fk_compensation_type",
        "fk_amf_compensation_type",
        "fk_plan_type",
        "fk_product",
        "fk_product_type",
        "fk_tier",
        "fk_county",
        "fk_region",
        "policy_contract_id",
        "fk_tx_type",
        "submit_from",
        "submit_to",
        "statement_from",
        "statement_to",
        "original_effective_from",
        "original_effective_to",
        "benefit_effective_from",
        "benefit_effective_to",
        "flat_rate",
        "exclude_admin_fee",
        "rate_type",
        "order",
        "fk_entry_user",
    ];  
    
    public function txtRateType(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Percentage", 1 => "Flat Rate", 2 => "Flat Rate per member"][$this->rate_type]
        );
    }

    
    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number", "id");
    }

    public function business_segment(){
        return $this->belongsTo(BusinessSegmentsModel::class, "fk_business_segment", "id");
    }

    public function business_type(){
        return $this->belongsTo(BusinessTypesModel::class, "fk_business_type", "id");
    }

    public function compensation_type(){
        return $this->belongsTo(CompensationTypesModel::class, "fk_compensation_type", "id");
    }

    public function amf_compensation_type(){
        return $this->belongsTo(AmfCompensationTypesModel::class, "fk_amf_compensation_type", "id");
    }

    public function plan_type(){
        return $this->belongsTo(PlanTypesModel::class, "fk_plan_type", "id");
    }

    public function product(){
        return $this->belongsTo(ProductsModel::class, "fk_product", "id");
    }

    public function product_type(){
        return $this->belongsTo(ProductTypesModel::class, "fk_product_type", "id");
    }

    public function tier(){
        return $this->belongsTo(TiersModel::class, "fk_tier", "id");
    }

    public function county(){
        return $this->belongsTo(CountiesModel::class, "fk_county", "id");
    }

    public function region(){
        return $this->belongsTo(RegionsModel::class, "fk_region", "id");
    }

    public function tx_type(){
        return $this->belongsTo(TxTypesModel::class, "fk_tx_type", "id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }



}
