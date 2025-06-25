<?php

namespace App\Exports;

use App\Models\Commissions\CommissionTransactionsModel;
use App\Models\Commissions\StatementsItemModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class AllSalesPivotTableReportExport
{
    protected $statement_date;
    protected $spreadsheet;
    protected $sheet;
    protected $data;
    protected $outputXlsm;

    public static function generate($statement_date, $outputXlsm)
    {
        $instance = new self();

        $instance->statement_date = $statement_date;
        $instance->outputXlsm = $outputXlsm;

        $instance->createSheet();
        $instance->addHeadersToSheet();
        $instance->getData();
        $instance->addDataToSheet();
        $instance->autosizeColumns();
        $instance->generateXlsx();
    }

    private function createSheet()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->sheet->setTitle('Details');
    }

    private function addHeadersToSheet()
    {
        $headers = [
            'Pay To',
            'Agent Number',
            'Agency Code',
            'Product Type',
            'Plan Description',
            'Member Count',
            'Subscriber Name',
            'CUID',
            'App ID',
            'Contract #',
            'Original Effective Date',
            'Benefit Effective Date',
            'Cancel Date',
            'Referral Rate Applied',
            'Referral Contract Rate',
            'Referral Commission',
            'Writting Rate Applied',
            'Writting Contract Rate',
            'Writting Commission',
            'Carrier',
            'Comp Type',
            'AMF Comp Type',
            'Is Qualified',
            'Plan Type',
            'Tier',
            'County',
            'Agent Title',
            'Agent Contract Type',
            'Agent Status',
            'Sales Region',
            'Agent Company',
            'Agent Company EIN',
            'Payroll/Emp ID',
        ];

        foreach ($headers as $i => $header) {
            $this->sheet->setCellValueExplicit([$i + 1, 1], $header, DataType::TYPE_INLINE);
        }
    }

    private function getData()
    {

        $this->data = [];
        $statementItems = StatementsItemModel::select("statement_items.*")
            ->join("statements", "statements.id", "=", "statement_items.fk_statement")
            ->where("statements.statement_date", "=", $this->statement_date)
            ->get();

        foreach ($statementItems as $statementItem) {
            $commissionTransaction = $statementItem->commission_transaction;
            $agentNumber = $statementItem->statement->agent_number;
            $policy = $commissionTransaction?->policy;
            $subscriberName = "";
            $cuid = "";
            if (isset($policy)) {
                $subscriberName = $policy->customer?->first_name . ' ' . $policy->customer?->last_name;
                $cuid = $policy->customer?->cuid?->name ?? "";
            } else {
                $subscriberName = $commissionTransaction->adjusment_subscriber;
            }
            $referralRateApplied = "";
            $referralContractRate = "";
            $referralCommission = 0;
            $writtingRateApplied = "";
            $writtingContractRate = "";
            $writtingCommission = 0;

            if ($statementItem->agent_type == 0) {
                $writtingRateApplied = $statementItem->flat_rate;
                $writtingContractRate = $agentNumber->contract_rate;
                $writtingCommission = $statementItem->comp_amount;
                //$writtingCommission = str_replace(".",",",$statementItem->comp_amount);
            } else {
                $referralRateApplied = $statementItem->flat_rate;
                $referralContractRate = $agentNumber->contract_rate;
                $referralCommission = $statementItem->comp_amount;
                //$referralCommission = str_replace(".",",",$statementItem->comp_amount);
            }

            array_push($this->data, [
                'payTo' => $agentNumber?->agency?->name ?? "",
                'agentNumber' => $agentNumber?->number ?? "",
                'agencyCode' => $agentNumber?->agency_code?->name ?? "",
                'productType' => $commissionTransaction->product_type?->name ?? "",
                'planDescription' => $commissionTransaction->product?->description ?? "",
                'memberCount' => $policy?->num_dependents ?? "",
                'subscriberName' => $subscriberName,
                'cuid' => $cuid,
                'appId' => $policy?->application_id ?? "",
                'contractNumber' => $policy?->contract_id ?? "",
                'originalEffectiveDate' => $policy?->original_effective_date ? date('m/d/Y', strtotime($policy->original_effective_date)) : '',
                'benefitEffectiveDate' => $policy?->benefit_effective_date ? date('m/d/Y', strtotime($policy->benefit_effective_date)) : '',
                'cancelDate' => $policy?->cancel_date ? date('m/d/Y', strtotime($policy->cancel_date)) : '',
                'referralRateApplied' => $referralRateApplied,
                'referralContractRate' => $referralContractRate,
                'referralCommission' => $referralCommission,
                'writtingRateApplied' => $writtingRateApplied,
                'writtingContractRate' => $writtingContractRate,
                'writtingCommission' => $writtingCommission,
                'carrier' => $agentNumber?->carrier?->name,
                'compType' => $commissionTransaction->compensation_type?->name ?? "",
                'amfCompType' => $commissionTransaction->amf_compensation_type?->name ?? "",
                'isQualified' => $commissionTransaction->is_qualified,
                'planType' => $commissionTransaction->plan_type?->name ?? "",
                'tier' => $commissionTransaction->tier?->name ?? "",
                'county' => $commissionTransaction->county?->name ?? "",
                'agentTitle' => $agentNumber?->agent_title?->name ?? "",
                'agentContractType' => $agentNumber?->agent?->contract_type?->name ?? "",
                'agentStatus' => $agentNumber?->agent_status?->name ?? "",
                'salesRegion' => $agentNumber?->agent?->sales_region?->name ?? "",
                'agentCompany' => $agentNumber?->agent?->company_name ?? "",
                'agentCompanyEin' => $agentNumber?->agent?->company_EIN ?? "",
                'payrollEmpId' => $agentNumber?->agent?->payroll_emp_ID ?? "",
            ]);
        }
    }

    private function addDataToSheet()
    {
        $row = 2;
        foreach ($this->data as $item) {
            $this->sheet->setCellValueExplicit([1, $row], $item["payTo"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([2, $row], $item["agentNumber"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([3, $row], $item["agencyCode"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([4, $row], $item["productType"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([5, $row], $item["planDescription"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([6, $row], $item["memberCount"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([7, $row], $item["subscriberName"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([8, $row], $item["cuid"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([9, $row], $item["appId"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([10, $row], $item["contractNumber"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([11, $row], $item["originalEffectiveDate"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([12, $row], $item["benefitEffectiveDate"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([13, $row], $item["cancelDate"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([14, $row], $item["referralRateApplied"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([15, $row], $item["referralContractRate"], DataType::TYPE_INLINE);
            $this->sheet->setCellValue([16, $row], $item["referralCommission"]);
            $this->sheet->setCellValueExplicit([17, $row], $item["writtingRateApplied"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([18, $row], $item["writtingContractRate"], DataType::TYPE_INLINE);
            $this->sheet->setCellValue([19, $row], $item["writtingCommission"]);
            $this->sheet->setCellValueExplicit([20, $row], $item["carrier"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([21, $row], $item["compType"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([22, $row], $item["amfCompType"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([23, $row], $item["isQualified"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([24, $row], $item["planType"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([25, $row], $item["tier"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([26, $row], $item["county"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([27, $row], $item["agentTitle"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([28, $row], $item["agentContractType"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([29, $row], $item["agentStatus"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([30, $row], $item["salesRegion"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([31, $row], $item["agentCompany"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([32, $row], $item["agentCompanyEin"], DataType::TYPE_INLINE);
            $this->sheet->setCellValueExplicit([33, $row], $item["payrollEmpId"], DataType::TYPE_INLINE);
            $row++;
        }
    } 

    private function autosizeColumns()
    {
        $highestColumnLetter = $this->sheet->getHighestColumn();

        foreach (range('A', $highestColumnLetter) as $column) {
            $this->sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function generateXlsx()
    {
        $templateXlsm = storage_path('app/templates/all_sales_report.xlsx');

        copy($templateXlsm, $this->outputXlsm);

        $tempXlsx = storage_path('app/temp_excel/temporal.xlsx');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempXlsx);

        $zipTemp = new ZipArchive;
        $zipOutput = new ZipArchive;

        $zipTemp->open($tempXlsx);
        $zipOutput->open($this->outputXlsm);

        $dataXml = $zipTemp->getFromName('xl/worksheets/sheet1.xml');

        $zipOutput->addFromString('xl/worksheets/sheet1.xml', $dataXml);

        $zipTemp->close();
        $zipOutput->close();
    }
}
