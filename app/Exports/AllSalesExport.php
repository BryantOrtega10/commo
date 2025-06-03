<?php

namespace App\Exports;

use App\Models\Commissions\CommissionTransactionsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllSalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $statement_date;

    public function __construct($statement_date)
    {
        $this->statement_date = $statement_date;
    }

    public function collection()
    {
        return CommissionTransactionsModel::select("commission_transactions.*")->where("statement_date", "=", $this->statement_date)->get();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->check_date ? date("m/d/Y",strtotime($row->check_date)) : "",
            $row->statement_date ? date("m/d/Y",strtotime($row->statement_date)) : "",
            $row->submit_date ? date("m/d/Y",strtotime($row->submit_date)) : "",
            $row->original_effective_date ? date("m/d/Y",strtotime($row->original_effective_date)) : "",
            $row->benefit_effective_date ? date("m/d/Y",strtotime($row->benefit_effective_date)) : "",
            $row->cancel_date ? date("m/d/Y",strtotime($row->cancel_date)) : "",
            $row->initial_payment_date ? date("m/d/Y",strtotime($row->initial_payment_date)) : "",
            $row->accounting_date ? date("m/d/Y",strtotime($row->accounting_date)) : "",
            $row->comp_amount,
            $row->flat_rate,
            $row->premium_percentaje,
            $row->premium_amount,
            $row->event_type,
            $row->exchange_ind,
            $row->is_adjustment,
            $row->comp_year,
            $row->is_qualified,
            $row->rate_type,
            $row->agency_code?->name ?? "",
            $row->carrier?->name ?? "",
            $row->business_segment?->name ?? "",
            $row->business_type?->name ?? "",
            $row->compensation_type?->name ?? "",
            $row->amf_compensation_type?->name ?? "",
            $row->plan_type?->name ?? "",
            $row->product?->description ?? "",
            $row->product_type?->name ?? "",
            $row->tier?->name ?? "",
            $row->county?->name ?? "",
            $row->policy?->name ?? "",
            $row->agent_number?->number ?? "",
            $row->adjusment_subscriber,
            $row->entry_user?->name ?? "",
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Check Date',
            'Statement Date',
            'Submit Date',
            'Original Effective Date',
            'Benefit Effective Date',
            'Cancel Date',
            'Initial Payment Date',
            'Accounting Date',
            'Compensation Amount',
            'Flat Rate',
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
            'Agent Number',
            'Adjustment Subscriber',
            'Entry User',
        ];
    }
}
