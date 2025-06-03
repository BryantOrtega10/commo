<?php

namespace App\Http\Controllers\Reports;

use App\Exports\AgentReportExport;
use App\Http\Controllers\Controller;
use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\SalesRegionModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AgentReportController extends Controller
{
    public function showAgentReport()
    {
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agentNumbers = AgentNumbersModel::all();
    
        return view('reports.showAgentReport',[
            "agency_codes" => $agency_codes,
            "sales_regions" => $sales_regions,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agentNumbers" => $agentNumbers,
        ]);
    }

    public function generateAgentReport(Request $request)
    {

        $agency_code = $request->input("agency_code");
        $agent_status = $request->input("agent_status");
        $mentor_agent_number = $request->input("mentor_agent_number");
        $override_agent_number = $request->input("override_agent_number");
        $sales_region = $request->input("sales_region");
        $agent_title = $request->input("agent_title");

        return Excel::download(new AgentReportExport(
            $agency_code,
            $agent_status,
            $mentor_agent_number,
            $override_agent_number,
            $sales_region,
            $agent_title,
        ), 'agents_report.xlsx');
    }
}
