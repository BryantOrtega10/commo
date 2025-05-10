<?php

namespace App\Models\Policies;

use App\Models\Agents\AgentNumbersModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\EnrollmentMethodsModel;
use App\Models\MultiTable\PolicyStatusModel;
use App\Models\Products\ProductsModel;
use App\Models\User;
use App\Models\Utils\FilesModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PoliciesModel extends Model
{
    //

    protected $table = "policies";

    protected $fillable = [
        "app_submit_date",
        "request_effective_date",
        "original_effective_date",
        "application_id",
        "eligibility_id",
        "proposal_id",
        "contract_id",
        "num_adults",
        "num_dependents",
        "premium",
        "cancel_date",
        "benefit_effective_date",
        "reenrollment",
        "entry_method",
        "validation_date",
        "validation_filename",
        "auto_entry_note",
        "auto_entry_date",
        "auto_entry_filename",
        "auto_entry_comp_type",
        "review_note",
        "non_commissionable",
        "fk_policy_status",
        "fk_customer",
        "fk_agent_number",
        "fk_product",
        "fk_enrollment_method",
        "fk_county",
        "fk_entry_user",
        "fk_agent_number_1",
        "fk_agent_number_2",
        "count_commisions",
    ];

    public function txtReenrollment(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "No", 1 => "Yes"][$this->reenrollment]
        );
    }

    public function txtEntryMethod(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Manual", 1 => "AutoEntry"][$this->entry_method]
        );
    }

    public function policy_status(){
        return $this->belongsTo(PolicyStatusModel::class, "fk_policy_status","id");
    }

    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer","id");
    }

    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number","id");
    }

    public function agent_number_1(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number_1","id");
    }

    public function agent_number_2(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number_2","id");
    }

    public function product(){
        return $this->belongsTo(ProductsModel::class, "fk_product","id");
    }

    public function enrollment_method(){
        return $this->belongsTo(EnrollmentMethodsModel::class, "fk_enrollment_method","id");
    }

    public function county(){
        return $this->belongsTo(CountiesModel::class, "fk_county","id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user","id");
    }

    public function files(){
        return $this->hasMany(FilesModel::class, "fk_policy","id");
    }

    public function dependents(){
        return $this->hasMany(DependentsModel::class, "fk_policy","id");
    }

}
