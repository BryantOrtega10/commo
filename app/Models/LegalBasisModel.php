<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class LegalBasisModel extends Model
{
    //
    protected $table = "legal_basis";

    protected $fillable = [
        "name",
        "description",
        "sort_order",
        "status",
    ];

    public function txtStatus(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Inactive", 1 => "Active"][$this->status]
        );
    }
}
