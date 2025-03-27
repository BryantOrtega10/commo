<?php

namespace App\Models\Agents;

use App\Models\MultiTable\AdminFeesModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AgentNumbersModel extends Model
{
    protected $table = "agent_numbers";

    protected $fillable = [
        "number",
        "fk_agency_code",
        "fk_carrier",
        "fk_agent_title",
        "fk_agent_status",
        "fk_agency",
        "contract_rate",
        "fk_admin_fee",
        "notes",
        "fk_agent",
        "fk_entry_user",
    ];

    public function agency_code()
    {
        return $this->belongsTo(AgencyCodesModel::class, "fk_agency_code", "id");
    }

    public function carrier()
    {
        return $this->belongsTo(CarriersModel::class, "fk_carrier", "id");
    }

    public function agent_title()
    {
        return $this->belongsTo(AgentTitlesModel::class, "fk_agent_title", "id");
    }

    public function agent_status()
    {
        return $this->belongsTo(AgentStatusModel::class, "fk_agent_status", "id");
    }

    public function agency()
    {
        return $this->belongsTo(AgenciesModel::class, "fk_agency", "id");
    }

    public function admin_fee()
    {
        return $this->belongsTo(AdminFeesModel::class, "fk_admin_fee", "id");
    }
    
    public function agent()
    {
        return $this->belongsTo(AgentsModel::class, "fk_agent", "id");
    }

    public function override_agent(){
        return $this->hasOne(AgentNumAgentModel::class, "fk_agent_number", "id")->where("type","=", 2);
    }

    public function mentor_agent(){
        return $this->hasOne(AgentNumAgentModel::class, "fk_agent_number", "id")->where("type", "=", 1);
    }

    public function entry_user()
    {
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }
}
