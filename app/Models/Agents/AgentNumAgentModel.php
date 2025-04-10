<?php

namespace App\Models\Agents;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AgentNumAgentModel extends Model
{
    protected $table = "agent_num_x_agent";

    protected $fillable = [
        "type",
        "fk_agent_number",
        "fk_agent",
        "start_date",
        "end_date",
    ];

    public function txtType(): Attribute {
        return Attribute::make(
            get: fn () => [1 => "Mentor Agent", 2 => "Override"][$this->type]
        );
    }

    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number","id");
    }

    public function agent(){
        return $this->belongsTo(AgentsModel::class, "fk_agent","id");
    }  

}
