<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CustomersModel extends Model
{
    //
    protected $table = "customers";

    protected $fillable = [
        "fk_business_type",
        "first_name",
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
        "fk_registration_s",
        "fk_customer",
    ];

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Inactive", 1 => "Active"][$this->status]
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

}
