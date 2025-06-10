<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Commissions\AddNewCommissionRateRequest;
use App\Http\Requests\Commissions\EditCommissionRateRequest;
use App\Models\Commissions\AmfCompensationTypesModel;
use App\Models\Commissions\CommissionRatesModel;
use App\Models\Commissions\CompensationTypesModel;
use App\Models\Commissions\TxTypesModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RegionsModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Products\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommissionRatesController extends Controller
{
    public function showCreateRow()
    {
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $compensation_types = CompensationTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $amf_compensation_types = AmfCompensationTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $products = ProductsModel::orderBy("description", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $tiers = TiersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $regions = RegionsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $txTypes = TxTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agentTypes = [0 => "Writting Agent", 1 => "Override Agent", 2 => "Mentor Agent", 3 => "Carrier Agent"];
        $rateTypes = [1 => "Percentage", 2 => "Flat Rate", 3 => "Flat Rate per member"];

        return view('agent_numbers.partials.commissionRateAddRow', [
            "business_segments" => $business_segments,
            "business_types" => $business_types,
            "compensation_types" => $compensation_types,
            "amf_compensation_types" => $amf_compensation_types,
            "plan_types" => $plan_types,
            "products" => $products,
            "product_types" => $product_types,
            "tiers" => $tiers,
            "counties" => $counties,
            "regions" => $regions,
            "txTypes" => $txTypes,
            "agentTypes" => $agentTypes,
            "rateTypes" => $rateTypes
        ]);
    }

    public function create(AddNewCommissionRateRequest $request)
    {

        CommissionRatesModel::where("fk_agent_number", "=", $request->input("idAgentNumber"))
            ->where("order", ">=", $request->input("order"))
            ->increment('order', 1);

        $entry_user = Auth::user();
        $commissionRate = new CommissionRatesModel();
        $commissionRate->fk_agent_number = $request->input("idAgentNumber");
        $commissionRate->fk_business_segment = $request->input("business_segment");
        $commissionRate->fk_business_type = $request->input("business_type");
        $commissionRate->fk_compensation_type = $request->input("compensation_type");
        $commissionRate->fk_amf_compensation_type = $request->input("amf_compensation_type");
        $commissionRate->fk_plan_type = $request->input("plan_type");
        $commissionRate->fk_product = $request->input("product");
        $commissionRate->fk_product_type = $request->input("product_type");
        $commissionRate->fk_tier = $request->input("tier");
        $commissionRate->fk_county = $request->input("county");
        $commissionRate->fk_region = $request->input("region");
        $commissionRate->policy_contract_id = $request->input("policy_contract_id");
        $commissionRate->fk_tx_type = $request->input("tx_type");
        $commissionRate->submit_from = $request->input("submit_from");
        $commissionRate->submit_to = $request->input("submit_to");
        $commissionRate->statement_from = $request->input("statement_from");
        $commissionRate->statement_to = $request->input("statement_to");
        $commissionRate->original_effective_from = $request->input("original_effective_from");
        $commissionRate->original_effective_to = $request->input("original_effective_to");
        $commissionRate->benefit_effective_from = $request->input("benefit_effective_from");
        $commissionRate->benefit_effective_to = $request->input("benefit_effective_to");
        $commissionRate->flat_rate = $request->input("flat_rate");
        $commissionRate->rate_type = $request->input("rate_type");
        $commissionRate->rate_amount = $request->input("rate_amount");
        $commissionRate->order = $request->input("order");
        $commissionRate->fk_entry_user = $entry_user->id;
        $commissionRate->save();

        Utils::createLog(
            "The user has created a new commission rate to agent number: ".$request->input("idAgentNumber"),
            "commissions.rate",
            "create"
        );
        return redirect(route('agent_numbers.update', ['id' => $request->input("idAgentNumber")]))->with('message', 'Commission Rate created successfully');
    }

    public function showUpdateRow($id)
    {
        $commissionRate = CommissionRatesModel::find($id);
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $compensation_types = CompensationTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $amf_compensation_types = AmfCompensationTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $products = ProductsModel::orderBy("description", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $tiers = TiersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $regions = RegionsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $txTypes = TxTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agentTypes = [0 => "Writting Agent", 1 => "Override Agent", 2 => "Mentor Agent", 3 => "Carrier Agent"];
        $rateTypes = [1 => "Percentage", 2 => "Flat Rate", 3 => "Flat Rate per member"];

        return view('agent_numbers.partials.commissionRateEditRow', [
            "commissionRate" => $commissionRate,
            "business_segments" => $business_segments,
            "business_types" => $business_types,
            "compensation_types" => $compensation_types,
            "amf_compensation_types" => $amf_compensation_types,
            "plan_types" => $plan_types,
            "products" => $products,
            "product_types" => $product_types,
            "tiers" => $tiers,
            "counties" => $counties,
            "regions" => $regions,
            "txTypes" => $txTypes,
            "agentTypes" => $agentTypes,
            "rateTypes" => $rateTypes
        ]);
    }
    
    public function update($id, EditCommissionRateRequest $request)
    {
        $commissionRate = CommissionRatesModel::find($id);
        $commissionRate->fk_business_segment = $request->input("business_segment");
        $commissionRate->fk_business_type = $request->input("business_type");
        $commissionRate->fk_compensation_type = $request->input("compensation_type");
        $commissionRate->fk_amf_compensation_type = $request->input("amf_compensation_type");
        $commissionRate->fk_plan_type = $request->input("plan_type");
        $commissionRate->fk_product = $request->input("product");
        $commissionRate->fk_product_type = $request->input("product_type");
        $commissionRate->fk_tier = $request->input("tier");
        $commissionRate->fk_county = $request->input("county");
        $commissionRate->fk_region = $request->input("region");
        $commissionRate->policy_contract_id = $request->input("policy_contract_id");
        $commissionRate->fk_tx_type = $request->input("tx_type");
        $commissionRate->submit_from = $request->input("submit_from");
        $commissionRate->submit_to = $request->input("submit_to");
        $commissionRate->statement_from = $request->input("statement_from");
        $commissionRate->statement_to = $request->input("statement_to");
        $commissionRate->original_effective_from = $request->input("original_effective_from");
        $commissionRate->original_effective_to = $request->input("original_effective_to");
        $commissionRate->benefit_effective_from = $request->input("benefit_effective_from");
        $commissionRate->benefit_effective_to = $request->input("benefit_effective_to");
        $commissionRate->flat_rate = $request->input("flat_rate");
        $commissionRate->rate_type = $request->input("rate_type");
        $commissionRate->rate_amount = $request->input("rate_amount");
        $commissionRate->save();

        Utils::createLog(
            "The user has updated a commission rate to agent number: ".$commissionRate->fk_agent_number,
            "commissions.rate",
            "update"
        );
        
        return redirect(route('agent_numbers.update', ['id' => $commissionRate->fk_agent_number]))->with('message', 'Commission Rate updated successfully');
    }

    public function delete($id)
    {
        $commissionRate = CommissionRatesModel::find($id);
        $agentNumber = $commissionRate->fk_agent_number;
        $commissionRate->delete();

        Utils::createLog(
            "The user has deleted a commission rate to agent number: ".$agentNumber,
            "commissions.rate",
            "delete"
        );

        return redirect(route('agent_numbers.update', ['id' => $agentNumber]))->with('message', 'Commission Rate deleted successfully');
    }
    
}
