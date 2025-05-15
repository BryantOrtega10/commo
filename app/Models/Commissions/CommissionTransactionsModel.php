<?php

namespace App\Models\Commissions;

use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CommissionTransactionsModel extends Model
{
    protected $table = "commission_transactions";

    protected $fillable = [
        "check_date",
        "statement_date",
        "submit_date",
        "original_effective_date",
        "benefit_effective_date",
        "cancel_date",
        "initial_payment_date",
        "accounting_date",
        "comp_amount",
        "flat_rate",
        "premium_percentaje",
        "premium_amount",
        "event_type",
        "exchange_ind",
        "is_adjustment",
        "comp_year",
        "is_qualified",
        "rate_type",
        "fk_agency_code",
        "fk_carrier",
        "fk_business_segment",
        "fk_business_type",
        "fk_compensation_type",
        "fk_amf_compensation_type",
        "fk_plan_type",
        "fk_product",
        "fk_product_type",
        "fk_tier",
        "fk_county",
        "fk_policy",
        "fk_agent_number",
        "fk_comm_upload_row",
        "adjusment_subscriber",
        "notes",
        "fk_entry_user",
    ];  

    


    public function agency_code(){
        return $this->belongsTo(AgencyCodesModel::class, "fk_agency_code","id");
    }

    public function carrier(){
        return $this->belongsTo(CarriersModel::class, "fk_carrier","id");
    }

    public function business_segment(){
        return $this->belongsTo(BusinessSegmentsModel::class, "fk_business_segment","id");
    }

    public function business_type(){
        return $this->belongsTo(BusinessTypesModel::class, "fk_business_type","id");
    }

    public function compensation_type(){
        return $this->belongsTo(CompensationTypesModel::class, "fk_compensation_type","id");
    }

    public function amf_compensation_type(){
        return $this->belongsTo(AmfCompensationTypesModel::class, "fk_amf_compensation_type","id");
    }

    public function plan_type(){
        return $this->belongsTo(PlanTypesModel::class, "fk_plan_type","id");
    }

    public function product(){
        return $this->belongsTo(ProductsModel::class, "fk_product","id");
    }

    public function product_type(){
        return $this->belongsTo(ProductTypesModel::class, "fk_product_type","id");
    }

    public function tier(){
        return $this->belongsTo(TiersModel::class, "fk_tier","id");
    }

    public function county(){
        return $this->belongsTo(CountiesModel::class, "fk_county","id");
    }

    public function policy(){
        return $this->belongsTo(PoliciesModel::class, "fk_policy","id");
    }

    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number","id");
    }

    public function commission_upload_row(){
        return $this->belongsTo(CommissionUploadRowsModel::class, "fk_comm_upload_row","id");
    }
   
    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user","id");
    }

}
