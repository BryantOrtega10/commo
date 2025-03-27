<?php

namespace App\Models\Agents;

use App\Models\MultiTable\ContractTypeModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\SalesRegionModel;
use App\Models\MultiTable\StatesModel;
use App\Models\User;
use App\Models\Utils\FilesModel;
use Illuminate\Database\Eloquent\Model;

class AgentsModel extends Model
{
    //
    protected $table = "agents";

    protected $fillable = [
        "first_name",
        "last_name",
        "date_birth",
        "ssn",
        "fk_gender",
        "email",
        "phone",
        "phone_2",
        "address",
        "address_2",
        "fk_state",
        "city",
        "zip_code",
        "national_producer",
        "license_number",
        "fk_sales_region",
        "has_CMS",
        "CMS_date",
        "has_marketplace_cert",
        "marketplace_cert_date",
        "contract_date",
        "payroll_emp_ID",
        "fk_contract_type",
        "company_EIN",
        "agent_notes",
        "fk_entry_user",
        "fk_user",
    ];

    public function gender()
    {
        return $this->belongsTo(GendersModel::class, "fk_gender", "id");
    }

    public function state()
    {
        return $this->belongsTo(StatesModel::class, "fk_state", "id");
    }

    public function sales_region()
    {
        return $this->belongsTo(SalesRegionModel::class, "fk_sales_region", "id");
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractTypeModel::class, "fk_contract_type", "id");
    }

    public function entry_user()
    {
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "fk_user", "id");
    }

    public function agent_numbers()
    {
        return $this->hasMany(AgentNumbersModel::class, "fk_agent", "id");
    }

    public function related_agents()
    {
        return $this->hasManyThrough(
            AgentNumbersModel::class, //Relacionado con...
            AgentNumAgentModel::class, //Tabla nxn
            'fk_agent',        // Clave foránea en nxn que referencia a este modelo
            'id',              // Clave primaria en Relacionado con...
            'id',              // Clave primaria en este modelo
            'fk_agent_number'  // Clave foránea en nxn que referencia a Relacionado con...
        );
    }

    public function files(){
        return $this->hasMany(FilesModel::class, "fk_agent","id");
    }
}
