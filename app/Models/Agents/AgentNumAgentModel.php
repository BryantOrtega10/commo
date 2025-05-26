<?php

namespace App\Models\Agents;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AgentNumAgentModel extends Model
{
    protected $table = "agent_num_x_agent";

    protected $fillable = [
        "type",
        "fk_agent_number_base",
        "fk_agent_number_rel",
        "start_date",
        "end_date",
    ];

    public function txtType(): Attribute
    {
        return Attribute::make(
            get: fn() => [1 => "Mentor Agent", 2 => "Override"][$this->type]
        );
    }

    public function agent_number_base()
    {
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number_base", "id");
    }

    public function agent_number_rel()
    {
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number_rel", "id");
    }
}
