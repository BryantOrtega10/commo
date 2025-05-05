<?php

namespace App\Models\Commissions;

use App\Models\MultiTable\CarriersModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CommissionUploadsModel extends Model
{
    protected $table = "commission_uploads";

    protected $fillable = [
        "name",
        "file_route",
        "rows_uploaded",
        "rows_processed",        
        "statement_date",
        "check_date",
        "processing_start_date",
        "processing_end_date",
        "status",
        "fk_template",
        "fk_carrier",
        "fk_entry_user",
    ];  
    
    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Uploading", 1 => "Uploaded", 2 => "Linking", 3 => "With errors", 4 => "Completed"][$this->status]
        );
    }
    
    public function template(){
        return $this->belongsTo(TemplatesModel::class, "fk_template", "id");
    }

    public function carrier(){
        return $this->belongsTo(CarriersModel::class, "fk_carrier", "id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }

    
    
}

