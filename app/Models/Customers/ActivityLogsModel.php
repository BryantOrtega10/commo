<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogsModel extends Model
{
    protected $table = "activity_logs";

    protected $fillable = [
        "description",
        "fk_activity",
        "fk_entry_user"
    ];

    public function activity(){
        return $this->belongsTo(ActivitiesModel::class, "fk_activity", "id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class,"fk_entry_user","id");
    }
    
}
