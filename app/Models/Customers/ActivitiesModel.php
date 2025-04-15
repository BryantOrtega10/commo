<?php

namespace App\Models\Customers;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ActivitiesModel extends Model
{
    //
    protected $table = "activities";

    protected $fillable = [
        "type",
        "description",
        "task_name",
        "expiration_date",
        "priority",
        "fk_customer",
        "isDone"
    ];

    public function txtDone(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Pending", 1 => "Done"][$this->isDone]
        );
    }

    public function txtType(): Attribute {
        return Attribute::make(
            get: fn () => [
                1 => "Note",
                2 => "Mail",
                3 => "Call",
                4 => "Meeting",
                5 => "Task",
            ][$this->type]
        );
    }   

    public function txtRemaining() : Attribute {
        return Attribute::make(
            get: function (){
                
                $expirationDate = new DateTime($this->expiration_date); 
                $now = new DateTime();

                $interval = $now->diff($expirationDate);
                $expired = $now > $expirationDate;

                $text = $expired ? 'Expired by: ' : '';
                if($interval->y > 0){
                    $text.= "$interval->y years, ";
                }
                if($interval->m > 0){
                    $text.= "$interval->m months, ";
                }
                if($interval->d > 0){
                    $text.= "$interval->d days, ";
                }
                if($interval->h > 0){
                    $text.= "$interval->h hours, ";
                }
                if($interval->i > 0){
                    $text.= "$interval->i minutes, ";
                }

                return $text;
            } 
        );
    }

    public function customer(){
        return $this->belongsTo(CustomersModel::class, "fk_customer", "id");
    }

    public function activityLogs(){
        return $this->hasMany(ActivityLogsModel::class, "fk_activity", "id");
    }
}
