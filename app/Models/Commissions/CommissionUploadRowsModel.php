<?php

namespace App\Models\Commissions;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CommissionUploadRowsModel extends Model
{
    //
    protected $table = "commission_upload_rows";

    protected $fillable = [
        "status",
        "notes",
        "data",
        "fk_commission_upload",
    ];  


    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Unlinked", 1 => "Linking", 2 => "Linked", 3 => "Error"][$this->status]
        );
    }

    public function commission_upload(){
        return $this->belongsTo(CommissionUploadsModel::class, "fk_commission_upload", "id");
    }

}
