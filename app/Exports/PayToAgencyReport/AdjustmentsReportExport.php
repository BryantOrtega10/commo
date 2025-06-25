<?php

namespace App\Exports\PayToAgencyReport;

use App\Models\Commissions\StatementsItemModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AdjustmentsReportExport implements FromView, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view("commissions.excel.adjustmentsTableExport", ['data' => $this->data]);
    }

    public function title(): string
    {
        return "Adjustments";
    }
}
