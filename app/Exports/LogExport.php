<?php

namespace App\Exports;

use App\Models\Commissions\CommissionTransactionsModel;
use App\Models\Utils\LogsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;
    protected $user_id;

    public function __construct($user_id, $start_date, $end_date)
    {
        $this->user_id = $user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {   
        $logs = LogsModel::where("fk_entry_user","=",$this->user_id);
        if($this->start_date !== null && $this->start_date != ""){
            $logs->where("created_at",">=", $this->start_date);
        }
        if($this->end_date !== null && $this->end_date != ""){
            $logs->where("created_at","<=", $this->end_date);
        }

        return $logs->get();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->description,
            $row->ip_address,
            $row->module,
            $row->action,
            $row->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Description',
            'IP',
            'Module',
            'Action',
            'Created_at'
        ];
    }
}
