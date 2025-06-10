<?php

namespace App\Http\Controllers\Reports;

use App\Exports\CustomerReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentsModel;
use App\Models\Policies\CountiesModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerReportController extends Controller
{
    public function showReport()
    {
        $agents = AgentsModel::orderBy("first_name", "ASC")->orderBy("last_name", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        Utils::createLog(
            "The user has entered the customer report",
            "reports.customer",
            "show"
        );
        return view('reports.showCustomerReport',[
            "agents" => $agents,
            "counties" => $counties
        ]);
    }

    public function generateReport(Request $request)
    {

        $date_birth_start = $request->input("date_birth_start");
        $date_birth_end = $request->input("date_birth_end");
        $city = $request->input("city");
        $county = $request->input("county");
        $contact_agent = $request->input("contact_agent");
        
        Utils::createLog(
            "The user has downloaded the customer report.",
            "reports.customer",
            "create"
        );
        return Excel::download(new CustomerReportExport(
            $date_birth_start,
            $date_birth_end,
            $city,
            $county,
            $contact_agent,
        ), 'customers_report.xlsx');
    }
}
