<?php

namespace App\Models\Commissions;

use App\Models\Agents\AgentNumbersModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatementsModel extends Model
{
    protected $table = "statements";

    protected $fillable = [
        "statement_date",
        "fk_agent_number",
        "number_policies",
        "total",
        "status",
        "fk_approve_user",
    ];  


    public function agent_number(){
        return $this->belongsTo(AgentNumbersModel::class, "fk_agent_number","id");
    }

    public function approve_user(){
        return $this->belongsTo(User::class, "fk_approve_user","id");
    }

}
