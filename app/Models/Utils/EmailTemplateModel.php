<?php

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateModel extends Model
{
    //
    protected $table = "email_template";

    protected $fillable = [
        "description"
    ];
}
