<?php

namespace App\Models\Commissions;

use App\Models\MultiTable\CarriersModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContractUploadsModel extends Model
{
    protected $table = "contract_uploads";

    protected $fillable = [
        "name",
        "file_route",
        "status",
        "headers",
        "processing_start_date",
        "processing_end_date",
        "fk_commission_upload",
        "fk_carrier",
        "fk_template",
        "fk_entry_user",
    ];  
    
    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Just uploaded", 1 => "Linking", 2 => "With errors", 3 => "Completed"][$this->status]
        );
    }

    public function commission_upload(){
        return $this->belongsTo(CommissionUploadsModel::class, "fk_commission_upload", "id");
    }

    public function carrier(){
        return $this->belongsTo(CarriersModel::class, "fk_carrier", "id");
    }

    public function template(){
        return $this->belongsTo(TemplatesModel::class, "fk_template", "id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }
    

}
