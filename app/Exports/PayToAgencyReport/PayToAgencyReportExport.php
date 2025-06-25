<?php

namespace App\Exports\PayToAgencyReport;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayToAgencyReportExport implements WithMultipleSheets
{
    protected $summaryData;
    protected $detailsData;
    protected $adjustmentsData;

    public function __construct(
        $summaryData,
        $detailsData,
        $adjustmentsData,
    )
    {
        $this->summaryData = $summaryData;
        $this->detailsData = $detailsData;
        $this->adjustmentsData = $adjustmentsData;
    }

    public function sheets(): array
    {
        return [
            new SummaryReportExport($this->summaryData),
            new DetailsReportExport($this->detailsData),
            new AdjustmentsReportExport($this->adjustmentsData),
        ];
    }
}
