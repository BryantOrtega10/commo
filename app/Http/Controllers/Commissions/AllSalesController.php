<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\AllSalesExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AllSalesController extends Controller
{
    public function showAllSalesForm(){
        return view('commissions.showAllSalesReport');
    }

    public function generateAllSalesReport(Request $request){

        if(!$request->has('statement_date') || empty($request->input('statement_date'))){
            return redirect(route('commissions.all-sales.show'))->with('error','Statement date is required');
        }

        return Excel::download(new AllSalesExport($request->input('statement_date')), 'all_sales_report.xlsx');

    }
}
