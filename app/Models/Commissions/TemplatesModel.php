<?php

namespace App\Models\Commissions;

use Illuminate\Database\Eloquent\Model;

class TemplatesModel extends Model
{
    protected $table = "templates";

    protected $fillable = [
        "name",
        "download_route"
    ];

}
