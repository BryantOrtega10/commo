<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\StatementBalancesExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatementBalancesReportController extends Controller
{
    public function showStatementBalances(){
        return view('commissions.showStatementBalances');
    }

    public function generateStatementBalances(Request $request){

        $statement_date = $request->input("statement_date");
        $showOnlyNegative = $request->has("showOnlyNegative");
        $minBalance = $request->input("minBalance");
        $maxBalance = $request->input("maxBalance");
        return Excel::download(new StatementBalancesExport($statement_date,$showOnlyNegative,$maxBalance,$minBalance), 'statement_balances.xlsx');
    }
}
