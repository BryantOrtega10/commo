<?php

namespace App\Models\Commissions;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContractUploadsRowsModel extends Model
{
    protected $table = "contract_uploads_rows";

    protected $fillable = [
        "status",
        "note",
        "data",
        "fk_comm_upload_rows",
        "fk_contract_uploads",
    ];  

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Unlinked", 1 => "Linking", 2 => "Linked"][$this->status]
        );
    }

    public function comm_upload_rows(){
        return $this->belongsTo(CommissionUploadRowsModel::class, "fk_comm_upload_rows", "id");
    }

    public function contract_uploads(){
        return $this->belongsTo(ContractUploadsModel::class, "fk_contract_uploads", "id");
    }

}
