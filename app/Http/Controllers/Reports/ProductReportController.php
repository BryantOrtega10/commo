<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ProductReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
{
    public function showReport()
    {
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        Utils::createLog(
            "The user has entered the product report",
            "reports.product",
            "show"
        );
        return view('reports.showProductReport',[
            'carriers' => $carriers,
            'business_types' => $business_types,
            'product_types' => $product_types,
            'plan_types' => $plan_types,
        ]);
    }

    public function generateReport(Request $request)
    {

        $carrier = $request->input("carrier");
        $business_type = $request->input("business_type");
        $description = $request->input("description");
        $plan_type = $request->input("plan_type");
        $product_type = $request->input("product_type");
        Utils::createLog(
            "The user has downloaded the product report.",
            "reports.product",
            "create"
        );
        return Excel::download(new ProductReportExport(
            $carrier,
            $business_type,
            $description,
            $plan_type,
            $product_type,
        ), 'products_report.xlsx');
    }
}
