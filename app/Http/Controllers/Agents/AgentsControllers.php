<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Agents\CreateAgentRequest;
use App\Http\Requests\Agents\EditAgentRequest;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\MultiTable\AdminFeesModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\ContractTypeModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\SalesRegionModel;
use App\Models\MultiTable\StatesModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;

class AgentsControllers extends Controller
{
    public function show(Request $request)
    {
        $agents = []; //DataTable
        Utils::createLog(
            "The user entered the agents list.",
            "agents.agents",
            "show"
        );
        session()->flashInput($request->all());

        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $defaultAgents = AgentsModel::all();


        return view('agents.show', [
            'genders' => $genders,
            'states' => $states,
            'sales_regions' => $sales_regions,
            'contract_types' => $contract_types,
            "agents" => $agents,
            "agency_codes" => $agency_codes,
            "carriers" => $carriers,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "defaultAgents" => $defaultAgents,
        ]);
    }

    public function showCreateForm()
    {
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        Utils::createLog(
            "The user has entered the form to create agents.",
            "agents.agents",
            "show"
        );
        return view('agents.create', [
            'genders' => $genders,
            'states' => $states,
            'sales_regions' => $sales_regions,
            'contract_types' => $contract_types
        ]);
    }

    public function datatableAjax(Request $request)
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
                case '0':
                    $agents->orderBy("id", $direction);
                    break;
                case '1':
                    $agents->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '4':
                    $agents->leftJoin('contract_types', 'agents.fk_contract_type', '=', 'contract_types.id')
                        ->orderBy('contract_types.name', $direction);
                    break;
                case '10':
                    $agents->orderBy("contract_date", $direction);
                    break;
                case '11':
                    $agents->orderBy("email", $direction);
                    break;
                case '12':
                    $agents->orderBy("phone", $direction);
                    break;

                case '2':
                    $agents
                        ->orderBy('agent_titles.name', $direction);
                    break;
                case '3':
                    $agents->orderBy('agent_status.name', $direction);
                    break;
                case '5':
                    $agents->orderBy('agency_codes.name', $direction);
                    break;
                case '6':
                    $agents->orderBy('agent_numbers.number', $direction);
                    break;
                case '7':
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

    public function search(Request $request){
        $agents = AgentsModel::select("agents.*");
        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $agents->where(function ($query) use ($searchTxt) {
                $query->where("agents.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.email", "like", "%{$searchTxt}%")
                    ->orWhere("agents.phone", "like", "%{$searchTxt}%")
                    ->orWhere("agents.id", "like", "%{$searchTxt}%");
            });
        }




        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $agents->orderBy("id", $direction);
                    break;
                case '1':
                    $agents->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '2':
                    $agents->orderBy("email", $direction);
                    break;
                case '3':
                    $agents->orderBy("phone", $direction);
                    break;
            }
        }


        $totalRecords = $agents->count();
        $agents = $agents->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($agents as $agent) {

            $filteredRecord = array();

            $filteredRecord["id"] = $agent->id;
            $filteredRecord["name"] = "$agent->first_name $agent->last_name";
            $filteredRecord["email"] = $agent->email;
            $filteredRecord["phone"] = $agent->phone;
            $filteredRecord["actions"]["name"] = "$agent->first_name $agent->last_name";
            $filteredRecord["actions"]["id"] = $agent->id;
            array_push($filteredRecords, $filteredRecord);
        }


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);

    }


    public function create(CreateAgentRequest $request)
    {

        $entry_user = Auth::user();

        $agent_user = new User();
        $agent_user->name = $request->input("first_name") . " " . $request->input("last_name");
        $agent_user->email = $request->input("email");
        $agent_user->password = Hash::make($request->input("ssn"));
        $agent_user->role = 'agent';
        $agent_user->save();

        $agent = new AgentsModel();
        $agent->first_name = $request->input("first_name");
        $agent->last_name = $request->input("last_name");
        $agent->date_birth = $request->input("date_birth");
        $agent->ssn = $request->input("ssn");
        $agent->fk_gender = $request->input("gender");
        $agent->email = $request->input("email");
        $agent->phone = $request->input("phone");
        $agent->phone_2 = $request->input("phone_2");
        $agent->address = $request->input("address");
        $agent->address_2 = $request->input("address_2");
        $agent->fk_state = $request->input("state");
        $agent->city = $request->input("city");
        $agent->zip_code = $request->input("zip_code");
        $agent->national_producer = $request->input("national_producer");
        $agent->license_number = $request->input("license_number");
        $agent->fk_sales_region = $request->input("sales_region");
        $agent->has_CMS = $request->has("has_CMS");
        $agent->CMS_date = $request->input("CMS_date");
        $agent->has_marketplace_cert = $request->has("has_marketplace_cert");
        $agent->marketplace_cert_date = $request->input("marketplace_cert_date");
        $agent->contract_date = $request->input("contract_date");
        $agent->payroll_emp_ID = $request->input("payroll_emp_ID");
        $agent->fk_contract_type = $request->input("contract_type");
        $agent->company_name = $request->input("company_name");
        $agent->company_EIN = $request->input("company_EIN");
        $agent->agent_notes = $request->input("agent_notes");
        $agent->fk_entry_user = $entry_user->id;
        $agent->fk_user = $agent_user->id;
        $agent->save();
        
        Utils::createLog(
            "The user has created a new agent with ID: " . $agent->id,
            "agents.agents",
            "create"
        );

        return redirect(route('agents.show'))->with('message', 'Agent created successfully');
    }


    public function showUpdateForm($id)
    {

        $agent = AgentsModel::find($id);
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $admin_fees = AdminFeesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agents = AgentNumbersModel::where("fk_agent", "<>", $id)->get();

        $selectedAgentNumber = null;
        $errors = session('errors', new ViewErrorBag);

        if ($errors->editAgentNumberForm->any()) {
            if ($errors->hasBag('editAgentNumberForm')) {
                $selectedAgentNumberID = session()->getOldInput('agent_number_id');
                $selectedAgentNumber = AgentNumbersModel::find($selectedAgentNumberID);
            }
        }

        Utils::createLog(
            "The user has entered the form to update Agents with ID: " . $agent->id,
            'agents.agents',
            "show"
        );

        return view('agents.update', [
            'agent' => $agent,
            'genders' => $genders,
            'states' => $states,
            'sales_regions' => $sales_regions,
            'contract_types' => $contract_types,
            "carriers" => $carriers,
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agencies" => $agencies,
            "admin_fees" => $admin_fees,
            "agents" => $agents,
            "selectedAgentNumber" => $selectedAgentNumber
        ]);
    }

    public function update($id, EditAgentRequest $request)
    {
        $agent = AgentsModel::find($id);
        $agent_user = User::find($agent->fk_user);
        $agent_user->name = $request->input("first_name") . " " . $request->input("last_name");
        $agent_user->email = $request->input("email");
        $agent_user->save();

        $agent->first_name = $request->input("first_name");
        $agent->last_name = $request->input("last_name");
        $agent->date_birth = $request->input("date_birth");
        $agent->ssn = $request->input("ssn");
        $agent->fk_gender = $request->input("gender");
        $agent->email = $request->input("email");
        $agent->phone = $request->input("phone");
        $agent->phone_2 = $request->input("phone_2");
        $agent->address = $request->input("address");
        $agent->address_2 = $request->input("address_2");
        $agent->fk_state = $request->input("state");
        $agent->city = $request->input("city");
        $agent->zip_code = $request->input("zip_code");
        $agent->national_producer = $request->input("national_producer");
        $agent->license_number = $request->input("license_number");
        $agent->fk_sales_region = $request->input("sales_region");
        $agent->has_CMS = $request->has("has_CMS");
        $agent->CMS_date = $request->input("CMS_date");
        $agent->has_marketplace_cert = $request->has("has_marketplace_cert");
        $agent->marketplace_cert_date = $request->input("marketplace_cert_date");
        $agent->contract_date = $request->input("contract_date");
        $agent->payroll_emp_ID = $request->input("payroll_emp_ID");
        $agent->fk_contract_type = $request->input("contract_type");
        $agent->company_name = $request->input("company_name");
        $agent->company_EIN = $request->input("company_EIN");
        $agent->agent_notes = $request->input("agent_notes");
        $agent->save();

        Utils::createLog(
            "The user has modified the agent with ID:" . $agent->id,
            'agents.agents',
            "update"
        );

        return redirect(route('agents.show'))->with('message', 'Agent updated successfully');
    }
}
