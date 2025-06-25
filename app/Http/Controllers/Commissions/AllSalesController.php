<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\AllSalesExport;
use App\Exports\AllSalesPivotTableReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class AllSalesController extends Controller
{
    public function showAllSalesForm()
    {
        Utils::createLog(
            "The user has entered the all sales menu.",
            "commissions.all-sales",
            "show"
        );

        return view('commissions.showAllSalesReport');
    }

    public function generateAllSalesReport(Request $request)
    {
        if (!$request->has('statement_date') || empty($request->input('statement_date'))) {
            return redirect(route('commissions.all-sales.show'))->with('error', 'Statement date is required');
        }

        Utils::createLog(
            "The user has downloaded the all sales report.",
            "commissions.all-sales",
            "create"
        );
        $outputXlsm = storage_path('app/temp_excel/finalReport.xlsx');
        AllSalesPivotTableReportExport::generate($request->input('statement_date'), $outputXlsm);
        return response()->download($outputXlsm, 'All Sales Report '. $request->input('statement_date') .'.xlsx')->deleteFileAfterSend(true);
    }
}
