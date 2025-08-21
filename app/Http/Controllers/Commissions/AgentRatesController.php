<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\CommissionRatesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\ContractTypeModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\SalesRegionModel;
use App\Models\MultiTable\StatesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentRatesController extends Controller
{
    public function showAgentRates(Request $request)
    {
        session()->flashInput($request->all());
        $agentNumbers = AgentNumbersModel::select("agent_numbers.*")
            ->join("agents", "agents.id", "=", "agent_numbers.fk_agent")
            ->orderBy("agents.last_name", "ASC")
            ->orderBy("agents.first_name", "ASC")
            ->orderBy("agent_numbers.id", "ASC")
            ->get();
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $defaultAgents = AgentsModel::all();

        Utils::createLog(
            "The user entered the agent rates.",
            "commissions.agent-rates",
            "show"
        );

        return view('commissions.showAgentRates', [
            "agentNumbers" => $agentNumbers,
            "agency_codes" => $agency_codes,
            "genders" => $genders,
            "states" => $states,
            "sales_regions" => $sales_regions,
            "contract_types" => $contract_types,
            "carriers" => $carriers,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "defaultAgents" => $defaultAgents,
        ]);
    }

    public function datatable(Request $request)
    {

        //DB::enableQueryLog(); 
        $agents = AgentsModel::select(
            "agents.*",
            "agent_titles.name as agent_title_name",
            "agent_status.name as agent_status_name",
            "agency_codes.name as agency_codes_name",
            "carriers.name as carriers_name",
            "agent_numbers.number",
            "agent_numbers.id as agent_number_id"
        )
            ->leftJoin('agent_numbers', 'agent_numbers.fk_agent', '=', 'agents.id')
            ->leftJoin('agent_titles', 'agent_numbers.fk_agent_title', '=', 'agent_titles.id')
            ->leftJoin('agent_status', 'agent_numbers.fk_agent_status', '=', 'agent_status.id')
            ->leftJoin('agency_codes', 'agent_numbers.fk_agency_code', '=', 'agency_codes.id')
            ->leftJoin('carriers', 'agent_numbers.fk_carrier', '=', 'carriers.id')
            ->with(["agent_numbers" => function ($query) use ($request) {
                if ($request->has("mentor_agent") && !empty($request->input("mentor_agent"))) {
                    $input = $request->input("mentor_agent");
                    $query->whereHas('mentor_agents', function ($subquery) use ($input) {
                        $subquery->where('fk_agent', "=", $input);
                    });
                }
                if ($request->has("override_agent") && !empty($request->input("override_agent"))) {
                    $input = $request->input("override_agent");
                    $query->whereHas('override_agents', function ($subquery) use ($input) {
                        $subquery->where('fk_agent', "=", $input);
                    });
                }
            }]);

        if ($request->has("agent_number") && !empty($request->input("agent_number"))) {
            $input = $request->input("agent_number");
            $agents->where('agent_numbers.number', "LIKE",  '%' . $input . '%');
        }

        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $agents->where('agent_numbers.fk_agency_code', "=", $input);
        }
        if ($request->has("agent_title") && !empty($request->input("agent_title"))) {
            $input = $request->input("agent_title");
            $agents->where('agent_numbers.fk_agent_title', "=", $input);
        }
        if ($request->has("agent_status") && !empty($request->input("agent_status"))) {
            $input = $request->input("agent_status");
            $agents->where('agent_numbers.fk_agent_status', "=", $input);
        }
        if ($request->has("carrier") && !empty($request->input("carrier"))) {
            $input = $request->input("carrier");
            $agents->where('agent_numbers.fk_carrier', "=", $input);
        }

        if ($request->has("mentor_agent") && !empty($request->input("mentor_agent"))) {
            $input = $request->input("mentor_agent");
            $agents->whereHas('agent_numbers', function ($query) use ($input) {
                $query->whereHas('mentor_agents', function ($subquery) use ($input) {
                    $subquery->where('fk_agent', "=", $input);
                });
            });
        }
        if ($request->has("override_agent") && !empty($request->input("override_agent"))) {
            $input = $request->input("override_agent");
            $agents->whereHas('agent_numbers', function ($query) use ($input) {
                $query->whereHas('override_agents', function ($subquery) use ($input) {
                    $subquery->where('fk_agent', "=", $input);
                });
            });
        }

        if ($request->has("first_name") && !empty($request->input("first_name"))) {
            $agents->where("agents.first_name", "LIKE", '%' . $request->input("first_name") . '%');
        }
        if ($request->has("last_name") && !empty($request->input("last_name"))) {
            $agents->where("agents.last_name", "LIKE", '%' . $request->input("last_name") . '%');
        }

        if ($request->has("email") && !empty($request->input("email"))) {
            $agents->where("agents.email", "LIKE", '%' . $request->input("email") . '%');
        }
        if ($request->has("phone") && !empty($request->input("phone"))) {
            $agents->where("agents.phone", "LIKE", '%' . $request->input("phone") . '%');
        }
        if ($request->has("contract_type") && !empty($request->input("contract_type"))) {
            $agents->where("agents.fk_contract_type", "=", $request->input("contract_type"));
        }

        if ($request->has("contract_date_from") && !empty($request->input("contract_date_from"))) {
            $agents->where("agents.contract_date", ">=", $request->input("contract_date_from"));
        }
        if ($request->has("contract_date_to") && !empty($request->input("contract_date_to"))) {
            $agents->where("agents.contract_date", "<=", $request->input("contract_date_to"));
        }


        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $agents->where(function ($query) use ($searchTxt) {
                $query->where("agents.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.email", "like", "%{$searchTxt}%")
                    ->orWhere("agents.phone", "like", "%{$searchTxt}%");
            });
        }




        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '1':
                    $agents->orderBy("id", $direction);
                    break;
                case '2':
                    $agents->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '5':
                    $agents->leftJoin('contract_types', 'agents.fk_contract_type', '=', 'contract_types.id')
                        ->orderBy('contract_types.name', $direction);
                    break;
                case '11':
                    $agents->orderBy("contract_date", $direction);
                    break;
                case '12':
                    $agents->orderBy("email", $direction);
                    break;
                case '13':
                    $agents->orderBy("phone", $direction);
                    break;

                case '3':
                    $agents
                        ->orderBy('agent_titles.name', $direction);
                    break;
                case '4':
                    $agents->orderBy('agent_status.name', $direction);
                    break;
                case '6':
                    $agents->orderBy('agency_codes.name', $direction);
                    break;
                case '7':
                    $agents->orderBy('agent_numbers.number', $direction);
                    break;
                case '8':
                    $agents->orderBy('carriers.name', $direction);
                    break;
            }
        }


        $totalRecords = $agents->count();
        $agents = $agents->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();
        //dd(DB::getQueryLog());


        foreach ($agents as $agent) {

            $filteredRecord = array();
            $filteredRecord["id"]["id"] = isset($agent->agent_number_id) ? $agent->agent_number_id : "-1";
            $filteredRecord["agent_id"] = $agent->id;
            $filteredRecord["agent"]["href"] = route('agents.update', ['id' => $agent->id]);
            $filteredRecord["agent"]["text"] = "$agent->first_name $agent->last_name";
            $filteredRecord["agent_title"] = $agent->agent_title_name;
            $filteredRecord["agent_status"] = $agent->agent_status_name;
            $filteredRecord["contract_type"] = $agent->contract_type?->name;
            $filteredRecord["agency_code"] = $agent->agency_code_name;
            if (isset($agent->agent_number_id)) {
                $filteredRecord["agent_number"]["href"] = route('agent_numbers.update', ['id' => $agent->agent_number_id]);
                $filteredRecord["agent_number"]["text"] = $agent->number;
            } else {
                $filteredRecord["agent_number"]["href"] = "";
                $filteredRecord["agent_number"]["text"] = "";
            }

            $filteredRecord["carrier"] = $agent->carrier_name;
            $overrideAgents = [];
            $mentorAgents = [];
            foreach ($agent->agent_numbers as $agent_number) {
                if ($agent_number->id == $agent->agent_number_id) {
                    foreach ($agent_number->override_agents as $override_agent) {
                        array_push($overrideAgents, $override_agent->agent_number_rel->agent->first_name . " " . $override_agent->agent_number_rel->agent->last_name);
                    }
                    foreach ($agent_number->mentor_agents as $mentor_agent) {
                        array_push($mentorAgents, $mentor_agent->agent_number_rel->agent->first_name . " " . $mentor_agent->agent_number_rel->agent->last_name);
                    }
                }
            }
            $filteredRecord["override_agents"]["items"] = $overrideAgents;
            $filteredRecord["mentor_agents"]["items"] = $mentorAgents;
            if (isset($agent->contract_date)) {
                $filteredRecord["contract_date"] = date('m/d/Y', strtotime($agent->contract_date));
            } else {
                $filteredRecord["contract_date"] = "";
            }
            $filteredRecord["email"] = $agent->email;
            $filteredRecord["phone"] = $agent->phone;
            array_push($filteredRecords, $filteredRecord);
        }


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);
    }

    public function appendRates(Request $request)
    {

        $entry_user = Auth::user();

        $ratesBase = CommissionRatesModel::where("fk_agent_number", "=", $request->input("agentNumberBase"))->get();

        CommissionRatesModel::whereIn("fk_agent_number", $request->input("agentNumberID"))
            ->increment('order', sizeof($ratesBase));

        foreach ($ratesBase as $rateBase) {
            foreach ($request->input("agentNumberID") as $agentNumberID) {
                $commissionRate = new CommissionRatesModel();
                $commissionRate->fk_agent_number = $agentNumberID;
                $commissionRate->fk_business_segment = $rateBase->fk_business_segment;
                $commissionRate->fk_business_type = $rateBase->fk_business_type;
                $commissionRate->fk_compensation_type = $rateBase->fk_compensation_type;
                $commissionRate->fk_amf_compensation_type = $rateBase->fk_amf_compensation_type;
                $commissionRate->fk_plan_type = $rateBase->fk_plan_type;
                $commissionRate->fk_product = $rateBase->fk_product;
                $commissionRate->fk_product_type = $rateBase->fk_product_type;
                $commissionRate->fk_tier = $rateBase->fk_tier;
                $commissionRate->fk_county = $rateBase->fk_county;
                $commissionRate->fk_region = $rateBase->fk_region;
                $commissionRate->policy_contract_id = $rateBase->policy_contract_id;
                $commissionRate->fk_tx_type = $rateBase->fk_tx_type;
                $commissionRate->agent_type = $rateBase->agent_type;
                $commissionRate->submit_from = $rateBase->submit_from;
                $commissionRate->submit_to = $rateBase->submit_to;
                $commissionRate->statement_from = $rateBase->statement_from;
                $commissionRate->statement_to = $rateBase->statement_to;
                $commissionRate->original_effective_from = $rateBase->original_effective_from;
                $commissionRate->original_effective_to = $rateBase->original_effective_to;
                $commissionRate->benefit_effective_from = $rateBase->benefit_effective_from;
                $commissionRate->benefit_effective_to = $rateBase->benefit_effective_to;
                $commissionRate->flat_rate = $rateBase->flat_rate;
                $commissionRate->rate_type = $rateBase->rate_type;
                $commissionRate->rate_amount = $rateBase->rate_amount;
                $commissionRate->order = $rateBase->order;
                $commissionRate->fk_entry_user = $entry_user->id;
                $commissionRate->save();
            }
        }

        Utils::createLog(
            "The user append agent rates from Agent Number ID: " . $request->input("agentNumberBase") . " to Agent Number IDs: " . implode(",", $request->input("agentNumberID", [])),
            "commissions.agent-rates",
            "create"
        );
        return redirect(route('commissions.agent-rates.show'))->with('message', 'Commission rates append successfully');
    }

    public function replicateRates($id, Request $request)
    {

        $ratesToDelete = CommissionRatesModel::leftJoin("statement_items", "statement_items.fk_commission_rate", "=", "commission_rates.id")
            ->whereIn("commission_rates.fk_agent_number", $request->input("agentNumberID"))
            ->whereNull("statement_items.id")
            ->delete();
        $entry_user = Auth::user();

        $ratesBase = CommissionRatesModel::where("fk_agent_number", "=", $request->input("agentNumberBase"))->get();

        CommissionRatesModel::whereIn("fk_agent_number", $request->input("agentNumberID"))
            ->increment('order', sizeof($ratesBase));

        foreach ($request->input("agentNumberID") as $agentNumberID) {
            foreach ($ratesBase as $rateBase) {
                $commissionRate = new CommissionRatesModel();
                $commissionRate->fk_agent_number = $agentNumberID;
                $commissionRate->fk_business_segment = $rateBase->fk_business_segment;
                $commissionRate->fk_business_type = $rateBase->fk_business_type;
                $commissionRate->fk_compensation_type = $rateBase->fk_compensation_type;
                $commissionRate->fk_amf_compensation_type = $rateBase->fk_amf_compensation_type;
                $commissionRate->fk_plan_type = $rateBase->fk_plan_type;
                $commissionRate->fk_product = $rateBase->fk_product;
                $commissionRate->fk_product_type = $rateBase->fk_product_type;
                $commissionRate->fk_tier = $rateBase->fk_tier;
                $commissionRate->fk_county = $rateBase->fk_county;
                $commissionRate->fk_region = $rateBase->fk_region;
                $commissionRate->policy_contract_id = $rateBase->policy_contract_id;
                $commissionRate->fk_tx_type = $rateBase->fk_tx_type;
                $commissionRate->agent_type = $rateBase->agent_type;
                $commissionRate->submit_from = $rateBase->submit_from;
                $commissionRate->submit_to = $rateBase->submit_to;
                $commissionRate->statement_from = $rateBase->statement_from;
                $commissionRate->statement_to = $rateBase->statement_to;
                $commissionRate->original_effective_from = $rateBase->original_effective_from;
                $commissionRate->original_effective_to = $rateBase->original_effective_to;
                $commissionRate->benefit_effective_from = $rateBase->benefit_effective_from;
                $commissionRate->benefit_effective_to = $rateBase->benefit_effective_to;
                $commissionRate->flat_rate = $rateBase->flat_rate;
                $commissionRate->rate_type = $rateBase->rate_type;
                $commissionRate->rate_amount = $rateBase->rate_amount;
                $commissionRate->order = $rateBase->order;
                $commissionRate->fk_entry_user = $entry_user->id;
                $commissionRate->save();
            }
            $usedRates = CommissionRatesModel::whereIn("fk_agent_number", "=", $agentNumberID)
                ->where("order", ">=", sizeof($ratesBase))
                ->get();

            $order = sizeof($ratesBase);
            foreach ($usedRates as $usedRate) {
                $usedRate->order = $order;
                $usedRate->update();
                $order++;
            }
        }
        Utils::createLog(
            "The user replicated agent rates from Agent Number ID: " . $request->input("agentNumberBase") . " to Agent Number IDs: " . implode(",", $request->input("agentNumberID", [])),
            "commissions.agent-rates",
            "create"
        );


        return redirect(route('commissions.agent-rates.show'))->with('message', 'Commission rates replicated successfully');
    }


    public function appendOneRate($id, Request $request)
    {
        $entry_user = Auth::user();

        $ratesBase = CommissionRatesModel::where("fk_agent_number", "=", $request->input("agentNumberBase"))->get();

        CommissionRatesModel::where("fk_agent_number", $id)->increment('order', sizeof($ratesBase));

        foreach ($ratesBase as $rateBase) {
            $commissionRate = new CommissionRatesModel();
            $commissionRate->fk_agent_number = $id;
            $commissionRate->fk_business_segment = $rateBase->fk_business_segment;
            $commissionRate->fk_business_type = $rateBase->fk_business_type;
            $commissionRate->fk_compensation_type = $rateBase->fk_compensation_type;
            $commissionRate->fk_amf_compensation_type = $rateBase->fk_amf_compensation_type;
            $commissionRate->fk_plan_type = $rateBase->fk_plan_type;
            $commissionRate->fk_product = $rateBase->fk_product;
            $commissionRate->fk_product_type = $rateBase->fk_product_type;
            $commissionRate->fk_tier = $rateBase->fk_tier;
            $commissionRate->fk_county = $rateBase->fk_county;
            $commissionRate->fk_region = $rateBase->fk_region;
            $commissionRate->policy_contract_id = $rateBase->policy_contract_id;
            $commissionRate->fk_tx_type = $rateBase->fk_tx_type;
            $commissionRate->agent_type = $rateBase->agent_type;
            $commissionRate->submit_from = $rateBase->submit_from;
            $commissionRate->submit_to = $rateBase->submit_to;
            $commissionRate->statement_from = $rateBase->statement_from;
            $commissionRate->statement_to = $rateBase->statement_to;
            $commissionRate->original_effective_from = $rateBase->original_effective_from;
            $commissionRate->original_effective_to = $rateBase->original_effective_to;
            $commissionRate->benefit_effective_from = $rateBase->benefit_effective_from;
            $commissionRate->benefit_effective_to = $rateBase->benefit_effective_to;
            $commissionRate->flat_rate = $rateBase->flat_rate;
            $commissionRate->rate_type = $rateBase->rate_type;
            $commissionRate->rate_amount = $rateBase->rate_amount;
            $commissionRate->order = $rateBase->order;
            $commissionRate->fk_entry_user = $entry_user->id;
            $commissionRate->save();        
        }

        Utils::createLog(
            "The user append agent rates from Agent Number ID: " . $request->input("agentNumberBase") . " to Agent Number ID: ". $id,
            "agents.agents-numbers.agent-rates",
            "update"
        );
        return redirect(route('agent_numbers.update', ['id' => $id]))->with('message', 'Commission rates append successfully');
    }
    public function replicateOneRate($id, Request $request)
    {
        $ratesToDelete = CommissionRatesModel::leftJoin("statement_items", "statement_items.fk_commission_rate", "=", "commission_rates.id")
            ->where("commission_rates.fk_agent_number", "=", $id)
            ->whereNull("statement_items.id")
            ->delete();
        $entry_user = Auth::user();

        $ratesBase = CommissionRatesModel::where("fk_agent_number", "=", $request->input("agentNumberBase"))->get();

        CommissionRatesModel::where("fk_agent_number", "=" , $id)->increment('order', sizeof($ratesBase));
       
        foreach ($ratesBase as $rateBase) {
            $commissionRate = new CommissionRatesModel();
            $commissionRate->fk_agent_number = $id;
            $commissionRate->fk_business_segment = $rateBase->fk_business_segment;
            $commissionRate->fk_business_type = $rateBase->fk_business_type;
            $commissionRate->fk_compensation_type = $rateBase->fk_compensation_type;
            $commissionRate->fk_amf_compensation_type = $rateBase->fk_amf_compensation_type;
            $commissionRate->fk_plan_type = $rateBase->fk_plan_type;
            $commissionRate->fk_product = $rateBase->fk_product;
            $commissionRate->fk_product_type = $rateBase->fk_product_type;
            $commissionRate->fk_tier = $rateBase->fk_tier;
            $commissionRate->fk_county = $rateBase->fk_county;
            $commissionRate->fk_region = $rateBase->fk_region;
            $commissionRate->policy_contract_id = $rateBase->policy_contract_id;
            $commissionRate->fk_tx_type = $rateBase->fk_tx_type;
            $commissionRate->agent_type = $rateBase->agent_type;
            $commissionRate->submit_from = $rateBase->submit_from;
            $commissionRate->submit_to = $rateBase->submit_to;
            $commissionRate->statement_from = $rateBase->statement_from;
            $commissionRate->statement_to = $rateBase->statement_to;
            $commissionRate->original_effective_from = $rateBase->original_effective_from;
            $commissionRate->original_effective_to = $rateBase->original_effective_to;
            $commissionRate->benefit_effective_from = $rateBase->benefit_effective_from;
            $commissionRate->benefit_effective_to = $rateBase->benefit_effective_to;
            $commissionRate->flat_rate = $rateBase->flat_rate;
            $commissionRate->rate_type = $rateBase->rate_type;
            $commissionRate->rate_amount = $rateBase->rate_amount;
            $commissionRate->order = $rateBase->order;
            $commissionRate->fk_entry_user = $entry_user->id;
            $commissionRate->save();
        }
        $usedRates = CommissionRatesModel::where("fk_agent_number", "=", $id)
            ->where("order", ">=", sizeof($ratesBase))
            ->get();

        $order = sizeof($ratesBase);
        foreach ($usedRates as $usedRate) {
            $usedRate->order = $order;
            $usedRate->update();
            $order++;
        }
        
        Utils::createLog(
            "The user replicated agent rates from Agent Number ID: " . $request->input("agentNumberBase") . " to Agent Number ID: ".$id,
            "agents.agents-numbers.agent-rates",
            "update"
        );

        return redirect(route('agent_numbers.update', ['id' => $id]))->with('message', 'Commission rates replicated successfully');
    }
}
