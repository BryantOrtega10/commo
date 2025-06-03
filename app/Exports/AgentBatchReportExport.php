<?php

namespace App\Exports;

use App\Models\Commissions\CommissionTransactionsModel;
use App\Models\Commissions\StatementsItemModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgentBatchReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $statement_id;

    public function __construct($statement_id)
    {
        $this->statement_id = $statement_id;
    }

    public function collection()
    {
        return StatementsItemModel::where("fk_statement", "=", $this->statement_id)->get();
    }

    public function map($row): array
    {
        

        return [
            $row->commission_transaction->id,
            $row->commission_transaction->agent_number?->number ?? "",
            $row->commission_transaction->agent_number?->agent?->first_name.' '.$row->commission_transaction->agent_number?->agent?->last_name,
            $row->commission_transaction->check_date ? date("m/d/Y",strtotime($row->commission_transaction->check_date)) : "",
            $row->commission_transaction->statement_date ? date("m/d/Y",strtotime($row->commission_transaction->statement_date)) : "",
            $row->commission_transaction->submit_date ? date("m/d/Y",strtotime($row->commission_transaction->submit_date)) : "",
            $row->commission_transaction->original_effective_date ? date("m/d/Y",strtotime($row->commission_transaction->original_effective_date)) : "",
            $row->commission_transaction->benefit_effective_date ? date("m/d/Y",strtotime($row->commission_transaction->benefit_effective_date)) : "",
            $row->commission_transaction->cancel_date ? date("m/d/Y",strtotime($row->commission_transaction->cancel_date)) : "",
            $row->commission_transaction->initial_payment_date ? date("m/d/Y",strtotime($row->commission_transaction->initial_payment_date)) : "",
            $row->commission_transaction->accounting_date ? date("m/d/Y",strtotime($row->commission_transaction->accounting_date)) : "",
            $row->commission_transaction->comp_amount,
            $row->commission_transaction->flat_rate,
            $row->commission_transaction->premium_percentaje,
            $row->commission_transaction->premium_amount,
            $row->commission_transaction->event_type,
            $row->commission_transaction->exchange_ind,
            $row->commission_transaction->is_adjustment,
            $row->commission_transaction->comp_year,
            $row->commission_transaction->is_qualified,
            $row->commission_transaction->rate_type,
            $row->commission_transaction->agency_code?->name ?? "",
            $row->commission_transaction->carrier?->name ?? "",
            $row->commission_transaction->business_segment?->name ?? "",
            $row->commission_transaction->business_type?->name ?? "",
            $row->commission_transaction->compensation_type?->name ?? "",
            $row->commission_transaction->amf_compensation_type?->name ?? "",
            $row->commission_transaction->plan_type?->name ?? "",
            $row->commission_transaction->product?->description ?? "",
            $row->commission_transaction->product_type?->name ?? "",
            $row->commission_transaction->tier?->name ?? "",
            $row->commission_transaction->county?->name ?? "",
            $row->commission_transaction->policy?->name ?? "",
            $row->commission_transaction->adjusment_subscriber,
            $row->commission_transaction->entry_user?->name ?? "",
            $row->comp_amount ?? 0,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Agent Number',
            'Agent Name',
            'Check Date',
            'Statement Date',
            'Submit Date',
            'Original Effective Date',
            'Benefit Effective Date',
            'Cancel Date',
            'Initial Payment Date',
            'Accounting Date',
            'Compensation Amount Base',
            'Flat Rate Base',
            'Premium Percentage',
            'Premium Amount',
            'Event Type',
            'Exchange Indicator',
            'Is Adjustment',
            'Compensation Year',
            'Is Qualified',
            'Rate Type',
            'Agency Code',
            'Carrier',
            'Business Segment',
            'Business Type',
            'Compensation Type',
            'AMF Compensation Type',
            'Plan Type',
            'Product',
            'Product Type',
            'Tier',
            'County',
            'Policy',
            'Adjustment Subscriber',
            'Entry User',
            'Compensation Amount Calculated',            
        ];
    }
}
