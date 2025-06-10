<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PolicyReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RegistrationSourcesModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PolicyReportController extends Controller
{
     public function showReport()
    {
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agentNumbers = AgentNumbersModel::all();
        Utils::createLog(
            "The user has entered the policy report",
            "reports.policy",
            "show"
        );
        return view('reports.showPoliciesReport',[
            'carriers' => $carriers,
            'business_segments' => $business_segments,
            'business_types' => $business_types,
            'product_types' => $product_types,
            'plan_types' => $plan_types,
            'registration_sources' => $registration_sources,
            'agentNumbers' => $agentNumbers,
        ]);
    }

    public function generateReport(Request $request)
    {

        $app_submit_date_start = $request->input("app_submit_date_start");
        $app_submit_date_end = $request->input("app_submit_date_end");
        $original_effective_date_start = $request->input("original_effective_date_start");
        $original_effective_date_end = $request->input("original_effective_date_end");
        $benefit_effective_date_start = $request->input("benefit_effective_date_start");
        $benefit_effective_date_end = $request->input("benefit_effective_date_end");
        $client_source = $request->input("client_source");
        $agent_number = $request->input("agent_number");
        $business_segment = $request->input("business_segment");
        $business_type = $request->input("business_type");
        $carrier = $request->input("carrier");
        $plan_type = $request->input("plan_type");
        $description = $request->input("description");
        $product_type = $request->input("product_type");
        Utils::createLog(
            "The user has downloaded the policy report.",
            "reports.policy",
            "create"
        );
        return Excel::download(new PolicyReportExport(
            $app_submit_date_start,
            $app_submit_date_end,
            $original_effective_date_start,
            $original_effective_date_end,
            $benefit_effective_date_start,
            $benefit_effective_date_end,
            $client_source,
            $agent_number,
            $business_segment,
            $business_type,
            $carrier,
            $plan_type,
            $description,
            $product_type,
        ), 'policies_report.xlsx');
    }
}