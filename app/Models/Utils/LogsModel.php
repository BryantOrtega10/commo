<?php

namespace App\Models\Utils;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LogsModel extends Model
{
    protected $table = "logs";

    protected $fillable = [
        "description",
        "ip_address",
        "module",
        "action",
        "fk_entry_user",
        
    ];

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }

}
