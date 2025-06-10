<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\AllSalesExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AllSalesController extends Controller
{
    public function showAllSalesForm(){
        Utils::createLog(
            "The user has entered the all sales menu.",
            "commissions.all-sales",
            "show"
        );

        return view('commissions.showAllSalesReport');
    }

    public function generateAllSalesReport(Request $request){

        if(!$request->has('statement_date') || empty($request->input('statement_date'))){
            return redirect(route('commissions.all-sales.show'))->with('error','Statement date is required');
        }

        Utils::createLog(
            "The user has downloaded the all sales report.",
            "commissions.all-sales",
            "create"
        );

        return Excel::download(new AllSalesExport($request->input('statement_date')), 'all_sales_report.xlsx');

    }
}
