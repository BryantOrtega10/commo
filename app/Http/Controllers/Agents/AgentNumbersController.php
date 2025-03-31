<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agents\CreateAgentNumbersRequest;
use App\Http\Requests\Agents\EditAgentNumbersRequest;
use App\Http\Requests\Agents\UpdateAgentNumbersRequest;
use App\Models\Agents\AgentNumAgentModel;
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
        $agents = AgentsModel::where("id", "<>", $id)->get();


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

    public function create($id, CreateAgentNumbersRequest $request)
    {

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
        
        for ($i = 1; $i <= 5; $i++) {
            //Mentors Agents
            $mentorAgentInput = "mentor_agent_" . $i;
            if ($request->has($mentorAgentInput) && !empty($request->input($mentorAgentInput))) {
                $mentorAgentID = $request->input($mentorAgentInput);
                $existMentorAgent = AgentNumAgentModel::where("fk_agent_number", "=", $agent_number->id)->where("fk_agent", "=", $mentorAgentID)->first();
                if (!isset($existMentorAgent)) {
                    $agentNumAgent = new AgentNumAgentModel();
                    $agentNumAgent->type = 1;
                    $agentNumAgent->fk_agent_number = $agent_number->id;
                    $agentNumAgent->fk_agent = $mentorAgentID;
                    $agentNumAgent->save();
                }
            }

            //Override Agents
            $overrideAgentInput = "override_agent_" . $i;
            if ($request->has($overrideAgentInput) && !empty($request->input($overrideAgentInput))) {
                $overrideAgentID = $request->input($overrideAgentInput);
                $existOverrideAgent = AgentNumAgentModel::where("fk_agent_number", "=", $agent_number->id)->where("fk_agent", "=", $overrideAgentID)->first();
                if (!isset($existOverrideAgent)) {
                    $agentNumAgent = new AgentNumAgentModel();
                    $agentNumAgent->type = 2;
                    $agentNumAgent->fk_agent_number = $agent_number->id;
                    $agentNumAgent->fk_agent = $overrideAgentID;
                    $agentNumAgent->save();
                }
            }
        }

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
        $agents = AgentsModel::where("id", "<>", $agent_number->fk_agent)->get();

        $mentorAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 1)->get()->toArray();
        $mentorAgents = array_column($mentorAgents,'fk_agent');
        
        $overrideAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 2)->get()->toArray();
        $overrideAgents = array_column($overrideAgents,'fk_agent');

        return view('agent_numbers.updateModal', [
            "agent_number" => $agent_number,
            "carriers" => $carriers,
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agencies" => $agencies,
            "admin_fees" => $admin_fees,
            "agents" => $agents,
            "mentorAgents" => $mentorAgents,
            "overrideAgents" => $overrideAgents,
        ]);
    }

    public function updateModal($id, EditAgentNumbersRequest $request){
        $agent_number = AgentNumbersModel::find($id);
        $this->update($id, $request);
        return redirect(route('agents.update', ['id' => $agent_number->fk_agent]))->with('message', 'Agent number updated successfully');
    }

    

    public function delete($id){
        $agent_number = AgentNumbersModel::find($id);
        $agentId =  $agent_number->fk_agent;
        // TODO: Validar que al eliminar no se lleve las relaciones
        $agent_number->delete();
        return redirect(route('agents.update', ['id' => $agentId]))->with('message', 'Agent number deleted successfully');
    }


    public function showUpdateForm($id)
    {
        $agent_number = AgentNumbersModel::find($id);
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agent_statuses = AgentStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $admin_fees = AdminFeesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agents = AgentsModel::where("id", "<>", $agent_number->fk_agent)->get();

        $mentorAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 1)->get()->toArray();
        $mentorAgents = array_column($mentorAgents,'fk_agent');
        
        $overrideAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 2)->get()->toArray();
        $overrideAgents = array_column($overrideAgents,'fk_agent');

        return view('agent_numbers.update', [
            "agent_number" => $agent_number,
            "carriers" => $carriers,
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agent_statuses" => $agent_statuses,
            "agencies" => $agencies,
            "admin_fees" => $admin_fees,
            "agents" => $agents,
            "mentorAgents" => $mentorAgents,
            "overrideAgents" => $overrideAgents,
        ]);
    }

    public function updateForm($id, UpdateAgentNumbersRequest $request){
        $this->update($id, $request);
        return redirect(route('agent_numbers.update', ['id' => $id]))->with('message', 'Agent number updated successfully');
    }

    

    private function update($id, Request $request)
    {
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

        $mentorAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 1)->get()->toArray();
        $mentorAgents = array_column($mentorAgents,'fk_agent');

        $overrideAgents = AgentNumAgentModel::select("fk_agent")->where("fk_agent_number", "=", $agent_number->id)->where("type", "=", 2)->get()->toArray();
        $overrideAgents = array_column($overrideAgents,'fk_agent');

        $arrMentorAgents = [];
        $arrOverrideAgents = [];
        for ($i = 1; $i <= 5; $i++) {
            $mentorAgentInput = "mentor_agent_" . $i;
            if ($request->has($mentorAgentInput) && !empty($request->input($mentorAgentInput))) {
                $mentorAgentID = $request->input($mentorAgentInput);
                array_push($arrMentorAgents, $mentorAgentID);
            }

            $overrideAgentInput = "override_agent_" . $i;
            if ($request->has($overrideAgentInput) && !empty($request->input($overrideAgentInput))) {
                $overrideAgentID = $request->input($overrideAgentInput);
                array_push($arrOverrideAgents, $overrideAgentID);
            }
        }


        $deleteMentorAgents = array_diff($mentorAgents, $arrMentorAgents);
        $deleteOverrideAgents = array_diff($overrideAgents, $arrOverrideAgents);

        
        AgentNumAgentModel::where("fk_agent_number", $agent_number->id)->whereIn("fk_agent", $deleteMentorAgents)->delete();
        AgentNumAgentModel::where("fk_agent_number", $agent_number->id)->whereIn("fk_agent", $deleteOverrideAgents)->delete();


        foreach ($arrMentorAgents as $mentorAgentID) {
            $existMentorAgent = AgentNumAgentModel::where("fk_agent_number", "=", $agent_number->id)->where("fk_agent", "=", $mentorAgentID)->first();
            if (!isset($existMentorAgent)) {
                $agentNumAgent = new AgentNumAgentModel();
                $agentNumAgent->type = 1;
                $agentNumAgent->fk_agent_number = $agent_number->id;
                $agentNumAgent->fk_agent = $mentorAgentID;
                $agentNumAgent->save();
            }
        }

        foreach ($arrOverrideAgents as $overrideAgentID) {
            $existOverrideAgent = AgentNumAgentModel::where("fk_agent_number", "=", $agent_number->id)->where("fk_agent", "=", $overrideAgentID)->first();
            if (!isset($existOverrideAgent)) {
                $agentNumAgent = new AgentNumAgentModel();
                $agentNumAgent->type = 2;
                $agentNumAgent->fk_agent_number = $agent_number->id;
                $agentNumAgent->fk_agent = $overrideAgentID;
                $agentNumAgent->save();
            }
        }        
    }
}
