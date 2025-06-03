<?php

namespace App\Models\Reports;

use App\Models\Commissions\StatementsModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AgentBatchReportItemModel extends Model
{
    protected $table = "agent_batch_report_item";

    protected $fillable = [
        "fk_report",
        "fk_statement",
        "status",
    ];  

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "not generated", 1 => "generated", 2 => "error"][$this->status]
        );
    }

    public function statement(){
        return $this->belongsTo(StatementsModel::class, "fk_statement", "id");
    }

    public function report(){
        return $this->belongsTo(AgentBatchReportModel::class, "fk_report", "id");
    }
}
