<?php

namespace App\Models\Commissions;


use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StatementsItemModel extends Model
{
    protected $table = "statement_items";

    protected $fillable = [
        "check_date",
        "fk_statement",
        "agent_type",
        "flat_rate",
        "rate_type",
        "comp_amount",
        "fk_commission_rate",
        "fk_commission_transaction",
        "fk_entry_user",
    ];  

    public function txtAgentType(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Writting Agent", 1 => "Mentor Agent", 2 => "Override Agent", 3 => "Carrier Agent"][$this->agent_type]
        );
    }

    public function txtRateType(): Attribute {
        return Attribute::make(
            get: fn () => [0 => "Percentage", 1 => "Flat Rate", 2 => "Flat Rate per member"][$this->rate_type]
        );
    }

    public function statement(){
        return $this->belongsTo(StatementsModel::class, "fk_statement", "id");
    }

    public function commission_rate(){
        return $this->belongsTo(CommissionRatesModel::class, "fk_commission_rate", "id");
    }

    public function commission_transaction(){
        return $this->belongsTo(CommissionTransactionsModel::class, "fk_commission_transaction", "id");
    }

    public function entry_user(){
        return $this->belongsTo(User::class, "fk_entry_user", "id");
    }

}
