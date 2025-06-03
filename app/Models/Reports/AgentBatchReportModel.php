<?php

namespace App\Models\Reports;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AgentBatchReportModel extends Model
{
    protected $table = "agent_batch_report";

    protected $fillable = [
        "statement_date",
        "export_file_type",
        "total",
        "generated",
        "processing_start_date",
        "processing_end_date",
        "status",
        "fk_entry_user",
    ];  

    public function txtExportFileType(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Pdf", 1 => "Excel"][$this->export_file_type]
        );
    }

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Generating", 1 => "Generated"][$this->status]
        );
    }
    
    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }
}
