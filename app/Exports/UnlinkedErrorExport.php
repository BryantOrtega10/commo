<?php

namespace App\Exports;

use App\Models\Commissions\CommissionTransactionsModel;
use App\Models\Commissions\CommissionUploadRowsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UnlinkedErrorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $statement_start_date;
    protected $statement_end_date;
    protected $agent_number;
    protected $agency_code;
    protected $carrier;
    protected $allMetadataKeys = [];


    public function __construct(
        $statement_start_date,
        $statement_end_date,
        $agent_number,
        $agency_code,
        $carrier
    ) {
        $this->statement_start_date = $statement_start_date;
        $this->statement_end_date = $statement_end_date;
        $this->agent_number = $agent_number;
        $this->agency_code = $agency_code;
        $this->carrier = $carrier;
        $this->allMetadataKeys = $this->getAllMetadataKeys();
    }


    protected function getAllMetadataKeys()
    {
        $keys = [];

        $errorRows = CommissionUploadRowsModel::select("commission_upload_rows.data")
                        ->join("commission_uploads", "commission_uploads.id", "=", "commission_upload_rows.fk_commission_upload")
                        ->leftJoin("commission_transactions", "commission_transactions.fk_comm_upload_row", "=", "commission_upload_rows.id")
                        ->whereIn("commission_upload_rows.status",[0,3]);
        
        if ($this->statement_start_date) {
            $errorRows->where('commission_uploads.statement_date', ">=", $this->statement_start_date);
        }
        if ($this->statement_end_date) {
            $errorRows->where('commission_uploads.statement_date', "<=", $this->statement_end_date);
        }
        if ($this->agent_number) {
            $errorRows->where('commission_transactions.fk_agent_number', "=", $this->agent_number);
        }
        if ($this->carrier) {
            $errorRows->where('commission_transactions.fk_carrier', "=", $this->carrier);
        }
        if ($this->agency_code) {
            $errorRows->where('commission_transactions.fk_agency_code', "=", $this->agency_code);
        }
        $errorRows = $errorRows->get();

        foreach ($errorRows as $errorRow) {
            $metadata = json_decode($errorRow->data, true);
            if (is_array($metadata)) {
                $keys = array_merge($keys, array_keys($metadata));
            }
        }

        return array_values(array_unique($keys));
    }


    public function collection()
    {
        $errorRows = CommissionUploadRowsModel::select("commission_upload_rows.*")
                        ->join("commission_uploads", "commission_uploads.id", "=", "commission_upload_rows.fk_commission_upload")
                        ->leftJoin("commission_transactions", "commission_transactions.fk_comm_upload_row", "=", "commission_upload_rows.id")
                        ->whereIn("commission_upload_rows.status",[0,3]);
        
        if ($this->statement_start_date) {
            $errorRows->where('commission_uploads.statement_date', ">=", $this->statement_start_date);
        }
        if ($this->statement_end_date) {
            $errorRows->where('commission_uploads.statement_date', "<=", $this->statement_end_date);
        }
        if ($this->agent_number) {
            $errorRows->where('commission_transactions.fk_agent_number', "=", $this->agent_number);
        }
        if ($this->carrier) {
            $errorRows->where('commission_transactions.fk_carrier', "=", $this->carrier);
        }
        if ($this->agency_code) {
            $errorRows->where('commission_transactions.fk_agency_code', "=", $this->agency_code);
        }

        return $errorRows->get();
                                        
    }

    public function map($row): array
    {
        $metadata = json_decode($row->data, true) ?? [];

        $mapped = [
            $row->id,
            $row->notes
        ];

        foreach ($this->allMetadataKeys as $key) {
            $mapped[] = $metadata[$key] ?? '';
        }

        return $mapped;
    }

    public function headings(): array
    {
        $headersTitle = array_map(function ($field) {
            return ucwords(str_replace('_', ' ', $field));
        }, $this->allMetadataKeys);
        return array_merge(
            ['ID'],
            $headersTitle
        );
        
    }
}
