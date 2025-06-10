<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PolicyCustomerReportExport;
use App\Exports\PolicyReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\EnrollmentMethodsModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\PolicyStatusModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RegistrationSourcesModel;
use App\Models\Policies\CountiesModel;
use App\Models\Products\ProductsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PolicyCustomerReportController extends Controller
{
    public function showReport()
    {
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agentNumbers = AgentNumbersModel::orderBy("number","asc")->get();
        
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $products = ProductsModel::orderBy("description","asc")->get();
        $policy_statuses = PolicyStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $enrollment_methods = EnrollmentMethodsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $users = User::orderBy("name","asc")->get();
        Utils::createLog(
            "The user has entered the policy and customer report",
            "reports.policy-customer",
            "show"
        );
        return view('reports.showPoliciesCustomerReport', [
            'carriers' => $carriers,
            'business_segments' => $business_segments,
            'business_types' => $business_types,
            'product_types' => $product_types,
            'plan_types' => $plan_types,
            'registration_sources' => $registration_sources,
            'agentNumbers' => $agentNumbers,
            'counties' => $counties,
            'products' => $products,
            'policy_statuses' => $policy_statuses,
            'enrollment_methods' => $enrollment_methods,
            'users' => $users,
        ]);
    }

    public function generateReport(Request $request)
    {

        $agent_number = $request->input("agent_number");
        $subscriber_name = $request->input("subscriber_name");
        $subscriber_date_birth = $request->input("subscriber_date_birth");
        $city = $request->input("city");
        $county = $request->input("county");
        $carrier = $request->input("carrier");
        $product = $request->input("product");
        $plan_type = $request->input("plan_type");
        $product_type = $request->input("product_type");
        $business_type = $request->input("business_type");
        $business_segment = $request->input("business_segment");
        $policy_status = $request->input("policy_status");
        $app_submit_date_start = $request->input("app_submit_date_start");
        $app_submit_date_end = $request->input("app_submit_date_end");
        $app_id = $request->input("app_id");
        $contract_num = $request->input("contract_num");
        $enrollment_method = $request->input("enrollment_method");
        $client_source = $request->input("client_source");
        $request_effective_date_start = $request->input("request_effective_date_start");
        $request_effective_date_end = $request->input("request_effective_date_end");
        $original_effective_date_start = $request->input("original_effective_date_start");
        $original_effective_date_end = $request->input("original_effective_date_end");
        $benefit_effective_date_start = $request->input("benefit_effective_date_start");
        $benefit_effective_date_end = $request->input("benefit_effective_date_end");
        $user = $request->input("user");
        $entry_date_start = $request->input("entry_date_start");
        $entry_date_end = $request->input("entry_date_end");
        Utils::createLog(
            "The user has downloaded the policy and customer report.",
            "reports.policy-customer",
            "create"
        );
        return Excel::download(new PolicyCustomerReportExport(
            $agent_number,
            $subscriber_name,
            $subscriber_date_birth,
            $city,
            $county,
            $carrier,
            $product,
            $plan_type,
            $product_type,
            $business_type,
            $business_segment,
            $policy_status,
            $app_submit_date_start,
            $app_submit_date_end,
            $app_id,
            $contract_num,
            $enrollment_method,
            $client_source,
            $request_effective_date_start,
            $request_effective_date_end,
            $original_effective_date_start,
            $original_effective_date_end,
            $benefit_effective_date_start,
            $benefit_effective_date_end,
            $user,
            $entry_date_start,
            $entry_date_end,
        ), 'Policies and Customer.xlsx');
    }
}
