<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agents\CreateAgentNumbersRequest;
use App\Http\Requests\Agents\EditAgentNumbersRequest;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\MultiTable\AdminFeesModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentNumbersController extends Controller
{
    public function showCreateForm($id)
    {
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $admin_fees = AdminFeesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agents = AgentsModel::where("id","<>",$id)->get();


        return view('agent_numbers.createModal', [
            "agentID" => $id,
            "carriers" => $carriers,
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agencies" => $agencies,
            "admin_fees" => $admin_fees,
            "agents" => $agents,
        ]);
    }

    public function create($id, CreateAgentNumbersRequest $request){

        $entry_user = Auth::user();

        $agent_number = new AgentNumbersModel();
        $agent_number->number = $request->input("number");
        $agent_number->fk_agency_code = $request->input("agency_code");
        $agent_number->fk_carrier = $request->input("carrier");
        $agent_number->fk_agent_title = $request->input("agent_title");
        $agent_number->fk_agent_status = $request->input("agent_status");
        $agent_number->fk_agency = $request->input("agency");
        $agent_number->contract_rate = $request->input("contract_rate");
        $agent_number->fk_admin_fee = $request->input("admin_fee");
        $agent_number->notes = $request->input("notes");
        $agent_number->fk_agent = $id;
        $agent_number->fk_entry_user = $entry_user->id;
        $agent_number->save();

        return redirect(route('agents.update', ['id' => $id]))->with('message', 'Agent number created successfully');
    }

    public function showUpdateModalForm($id)
    {
        $agent_number = AgentNumbersModel::find($id);
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $admin_fees = AdminFeesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agents = AgentsModel::where("id","<>",$id)->get();


        return view('agent_numbers.updateModal', [
            "agent_number" => $agent_number,
            "carriers" => $carriers,
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agencies" => $agencies,
            "admin_fees" => $admin_fees,
            "agents" => $agents,
        ]);
    }

    public function update($id, EditAgentNumbersRequest $request){

        $agent_number = AgentNumbersModel::find($id);
        $agent_number->number = $request->input("number");
        $agent_number->fk_agency_code = $request->input("agency_code");
        $agent_number->fk_carrier = $request->input("carrier");
        $agent_number->fk_agent_title = $request->input("agent_title");
        $agent_number->fk_agent_status = $request->input("agent_status");
        $agent_number->fk_agency = $request->input("agency");
        $agent_number->contract_rate = $request->input("contract_rate");
        $agent_number->fk_admin_fee = $request->input("admin_fee");
        $agent_number->notes = $request->input("notes");
        $agent_number->save();

        return redirect(route('agents.update', ['id' => $agent_number->fk_agent]))->with('message', 'Agent number updated successfully');
    }
     
}
