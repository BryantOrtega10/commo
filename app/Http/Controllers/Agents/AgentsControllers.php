<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agents\CreateAgentRequest;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;

class AgentsControllers extends Controller
{
    public function show(Request $request)
    {
        $agents = AgentsModel::with("agent_numbers");

        $agents = $agents->get();

        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('agents.show', [
            'genders' => $genders,
            'states' => $states,
            'sales_regions' => $sales_regions,
            'contract_types' => $contract_types,
            "agents" => $agents,
            "agency_codes" => $agency_codes
        ]);
    }

    public function showCreateForm(){
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $sales_regions = SalesRegionModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $contract_types = ContractTypeModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('agents.create', [
            'genders' => $genders,
            'states' => $states,
            'sales_regions' => $sales_regions,
            'contract_types' => $contract_types
        ]);
    }
    
    public function create(CreateAgentRequest $request){
        //dd($request->has("has_CMS"));

        $entry_user = Auth::user();
        
        $agent_user = new User();
        $agent_user->name = $request->input("first_name")." ".$request->input("last_name");
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
        $agent->company_EIN = $request->input("company_EIN");
        $agent->agent_notes = $request->input("agent_notes");
        $agent->fk_entry_user = $entry_user->id;
        $agent->fk_user = $agent_user->id;
        $agent->save();

        return redirect(route('agents.show'))->with('message', 'Agent created successfully');
    }


    public function showUpdateForm($id){

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
        $agents = AgentsModel::where("id","<>",$id)->get();

        $selectedAgentNumber = null;
        $errors = session('errors', new ViewErrorBag);

        if ($errors->editAgentNumberForm->any()) {
            if ($errors->hasBag('editAgentNumberForm')) {
                $selectedAgentNumberID = session()->getOldInput('agent_number_id');
                $selectedAgentNumber = AgentNumbersModel::find($selectedAgentNumberID);
            }    
        }


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

    


}
