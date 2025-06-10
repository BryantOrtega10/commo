<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\UnlinkedErrorExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\CarriersModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UnlinkedErrorReportController extends Controller
{
    public function showUnlinkedErrorReport(){
        $agentNumbers = AgentNumbersModel::select("agent_numbers.*")
                                         ->join("agents", "agents.id", "=","agent_numbers.fk_agent")
                                         ->orderBy("agents.last_name", "ASC")
                                         ->orderBy("agents.first_name", "ASC")
                                         ->orderBy("agent_numbers.id", "ASC")
                                         ->get();
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        Utils::createLog(
            "The user has entered the unlinked report",
            "commissions.unlinked",
            "show"
        );
        return view('commissions.showUnlinkedErrorReport',[
            "agentNumbers" => $agentNumbers,
            "agency_codes" => $agency_codes,
            "carriers" => $carriers,
        ]);
    }

    public function generateUnlinkedErrorReport(Request $request){
        
        Utils::createLog(
            "The user has downloaded the unlinked report.",
            "commissions.unlinked",
            "create"
        );
        return Excel::download(new UnlinkedErrorExport(
                $request->input('statement_start_date'),
                $request->input('statement_end_date'),
                $request->input('agentNumberBase'),
                $request->input('agency_code'),
                $request->input('carrier'),
        ), 'unlinked_error_report.xlsx');
        
    }
    
}
