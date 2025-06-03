<?php

namespace App\Exports;

use App\Models\Commissions\StatementsItemModel;
use App\Models\Commissions\StatementsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StatementBalancesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $statement_date;
    protected $showOnlyNegative;
    protected $maxBalance;
    protected $minBalance;

    public function __construct($statement_date, $showOnlyNegative, $maxBalance, $minBalance)
    {
        $this->statement_date = $statement_date;
        $this->showOnlyNegative = $showOnlyNegative;
        $this->maxBalance = $maxBalance;
        $this->minBalance = $minBalance;
    }

    public function collection()
    {
        $statements = StatementsModel::select("statements.*");
        if($this->statement_date !== null && $this->statement_date !== ""){
            $statements->where("statement_date","=",$this->statement_date);
        }
        if($this->showOnlyNegative){
            $statements->where("total","<",0);
        }
        if($this->maxBalance !== null && $this->maxBalance !== ""){
            $statements->where("total","<",$this->maxBalance);
        }
        if($this->minBalance !== null && $this->minBalance !== ""){
            $statements->where("total",">",$this->minBalance );
        }
        return $statements->get();

    }

    public function map($row): array
    {       

        return [
            date('m/d/Y', strtotime($row->statement_date)),
            $row->agent_number?->agent?->first_name." ".$row->agent_number?->agent?->last_name,
            $row->agent_number->number,
            $row->agent_number->agent_status?->name,
            $row->agent_number->agency?->name,
            $row->agent_number->carrier?->name,
            $row->total,
        ];
    }

    public function headings(): array
    {
        return [
            'Statement Date',
            'Agent',
            'Agent Number',
            'Agent Status',
            'Pay to Agency',
            'Carrier',
            'Statement Balance'
        ];
    }
}
