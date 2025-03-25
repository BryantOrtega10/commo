<?php

namespace App\Models\Utils;

use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Customers\CustomersModel;
use Illuminate\Database\Eloquent\Model;

class FilesModel extends Model
{
    protected $table = "files";

    protected $fillable = [
        "name",
        "route",
        "fk_customer",
        "fk_agent",
        "fk_agent_number"
    ];

    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer", "id");
    }

    public function agent(){
        return $this->belongsTo(AgentsModel::class, "fk_agent", "id");
    }

    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number", "id");
    }
}
