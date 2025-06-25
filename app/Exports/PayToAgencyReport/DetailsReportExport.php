<?php

namespace App\Exports\PayToAgencyReport;

use App\Models\Commissions\StatementsItemModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailsReportExport implements FromView, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view("commissions.excel.detailsTableExport", ['data' => $this->data]);
    }

    public function title(): string
    {
        return "Details";
    }
}
