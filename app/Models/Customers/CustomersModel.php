<?php

namespace App\Models\Customers;

use App\Models\Agents\AgentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\LegalBasisModel;
use App\Models\MultiTable\MaritalStatusModel;
use App\Models\MultiTable\PhasesModel;
use App\Models\MultiTable\RegistrationSourcesModel;
use App\Models\MultiTable\SuffixesModel;
use App\Models\Policies\CountiesModel;
use App\Models\Policies\PoliciesModel;
use App\Models\User;
use App\Models\Utils\FilesModel;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CustomersModel extends Model
{
    //
    protected $table = "customers";

    protected $fillable = [
        "fk_business_type",
        "first_name",
        "middle_initial",
        "last_name",
        "fk_suffix",
        "date_birth",
        "ssn",
        "fk_gender",
        "fk_marital_status",
        "email",
        "address",
        "address_2",
        "fk_county",
        "city",
        "zip_code",
        "phone",
        "phone_2",
        "fk_registration_s",
        "fk_customer",
        "fk_status",
        "fk_phase",
        "fk_legal_basis",
        "fk_agent",
        "fk_entry_user"
    ];

    public function txtAge(): Attribute {
        return Attribute::make(
            get: function () {
                if(empty($this->date_birth)){
                    return "";
                }
                $date_birth = new DateTime($this->date_birth);
                $today = new DateTime();

                $age = $date_birth->diff($today);
                //dd($age);
                return $age->y;
            } 
        );
    }

    public function business_type(){
        return $this->belongsTo(BusinessTypesModel::class,"fk_business_type","id");
    }

    public function suffix(){
        return $this->belongsTo(SuffixesModel::class,"fk_suffix","id");
    }

    public function gender(){
        return $this->belongsTo(GendersModel::class,"fk_gender","id");
    }

    public function marital_status(){
        return $this->belongsTo(MaritalStatusModel::class,"fk_marital_status","id");
    }

    public function county(){
        return $this->belongsTo(CountiesModel::class,"fk_county","id");
    }

    public function registration_s(){
        return $this->belongsTo(RegistrationSourcesModel::class,"fk_registration_s","id");
    }

    public function customer(){
        return $this->belongsTo(CustomersModel::class,"fk_customer","id");
    }

    public function status(){
        return $this->belongsTo(CustomerStatusModel::class,"fk_status","id");
    }

    public function phase(){
        return $this->belongsTo(PhasesModel::class,"fk_phase","id");
    }

    public function legal_basis(){
        return $this->belongsTo(LegalBasisModel::class,"fk_legal_basis","id");
    }

    public function agent(){
        return $this->belongsTo(AgentsModel::class,"fk_agent","id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class,"fk_entry_user","id");
    }

    public function files(){
        return $this->hasMany(FilesModel::class, "fk_customer","id");
    }

    public function cuids(){
        return $this->hasMany(CuidsModel::class, "fk_customer","id");
    }

    public function cuid(){
        return $this->hasOne(CuidsModel::class, "fk_customer","id");
    }

    public function policies(){
        return $this->hasMany(PoliciesModel::class, "fk_customer","id");
    }
}
