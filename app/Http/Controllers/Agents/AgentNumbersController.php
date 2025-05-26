<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agents\CreateAgentNumbersRequest;
use App\Http\Requests\Agents\EditAgentNumbersRequest;
use App\Http\Requests\Agents\UpdateAgentNumbersRequest;
use App\Models\Agents\AgentNumAgentModel;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\AmfCompensationTypesModel;
use App\Models\Commissions\CommissionRatesModel;
use App\Models\Commissions\CompensationTypesModel;
use App\Models\Commissions\TxTypesModel;
use App\Models\MultiTable\AdminFeesModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RegionsModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Products\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;

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
        $agents = AgentNumbersModel::where("fk_agent", "<>", $id)->get();


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
                $startDate = $request->input("start_date_ment_".$i);
                $endDate = $request->input("end_date_ment_".$i);

                $existMentorAgent = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)
                                                      ->where("fk_agent_number_rel", "=", $mentorAgentID)->first();
                if (!isset($existMentorAgent)) {
                    $agentNumAgent = new AgentNumAgentModel();
                    $agentNumAgent->type = 1;
                    $agentNumAgent->fk_agent_number_base = $agent_number->id;
                    $agentNumAgent->fk_agent_number_rel = $mentorAgentID;
                    $agentNumAgent->start_date = $startDate;
                    $agentNumAgent->end_date = $endDate;
                    $agentNumAgent->save();
                }
            }

            //Override Agents
            $overrideAgentInput = "override_agent_" . $i;
            if ($request->has($overrideAgentInput) && !empty($request->input($overrideAgentInput))) {
                $overrideAgentID = $request->input($overrideAgentInput);
                $startDate = $request->input("start_date_over_".$i);
                $endDate = $request->input("end_date_over_".$i);
                
                $existOverrideAgent = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)
                                                        ->where("fk_agent_number_rel", "=", $overrideAgentID)->first();
                if (!isset($existOverrideAgent)) {
                    $agentNumAgent = new AgentNumAgentModel();
                    $agentNumAgent->type = 2;
                    $agentNumAgent->fk_agent_number_base = $agent_number->id;
                    $agentNumAgent->fk_agent_number_rel = $overrideAgentID;
                    $agentNumAgent->start_date = $startDate;
                    $agentNumAgent->end_date = $endDate;
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
        $agents = AgentNumbersModel::where("fk_agent", "<>", $agent_number->fk_agent)->get();

        $mentorAgentsDB = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)
                                            ->where("type", "=", 1)->get();
        $mentorAgents = array();
        foreach($mentorAgentsDB as $mentorAgent){
            array_push($mentorAgents , ["id" => $mentorAgent->fk_agent_number_rel, "start_date" => $mentorAgent->start_date, "end_date" => $mentorAgent->end_date]);
        }
        
        $overrideAgentsDB = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)
                                              ->where("type", "=", 2)->get();
        $overrideAgents = array();
        foreach($overrideAgentsDB as $overrideAgent){
            array_push($overrideAgents , ["id" => $overrideAgent->fk_agent_number_rel, "start_date" => $overrideAgent->start_date, "end_date" => $overrideAgent->end_date]);
        }
        

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
        $agents = AgentNumbersModel::where("fk_agent", "<>", $agent_number->fk_agent)->get();

        $mentorAgentsDB = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)->where("type", "=", 1)->get();
        $mentorAgents = array();
        foreach($mentorAgentsDB as $mentorAgent){
            array_push($mentorAgents , ["id" => $mentorAgent->fk_agent_number_rel, "start_date" => $mentorAgent->start_date, "end_date" => $mentorAgent->end_date]);
        }
        
        $overrideAgentsDB = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)->where("type", "=", 2)->get();
        $overrideAgents = array();
        foreach($overrideAgentsDB as $overrideAgent){
            array_push($overrideAgents , ["id" => $overrideAgent->fk_agent_number_rel, "start_date" => $overrideAgent->start_date, "end_date" => $overrideAgent->end_date]);
        }
        
        $commissionRates = CommissionRatesModel::where("fk_agent_number","=",$id)->orderBy("order", "ASC")->get();
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
        

        $selectedCommissionRate = null;
        $errors = session('errors', new ViewErrorBag);

        if ($errors->editRate->any()) {
            if ($errors->hasBag('editRate')) {
                $selectedCommissionRateId = session()->getOldInput('commissionRateId');
                $selectedCommissionRate = CommissionRatesModel::find($selectedCommissionRateId);
            }
        }

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
            "commissionRates" => $commissionRates,
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
            "rateTypes" => $rateTypes,
            "selectedCommissionRate" => $selectedCommissionRate
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

        $mentorAgents = AgentNumAgentModel::select("fk_agent_number_rel")->where("fk_agent_number_base", "=", $agent_number->id)->where("type", "=", 1)->get()->toArray();
        $mentorAgents = array_column($mentorAgents,'fk_agent_number_rel');

        $overrideAgents = AgentNumAgentModel::select("fk_agent_number_rel")->where("fk_agent_number_base", "=", $agent_number->id)->where("type", "=", 2)->get()->toArray();
        $overrideAgents = array_column($overrideAgents,'fk_agent_number_rel');

        $arrMentorAgents = [];
        $arrMentorAgentsDates = [];
        $arrOverrideAgents = [];
        $arrOverrideAgentsDates = [];
        for ($i = 1; $i <= 5; $i++) {
            $mentorAgentInput = "mentor_agent_" . $i;
            if ($request->has($mentorAgentInput) && !empty($request->input($mentorAgentInput))) {
                $mentorAgentID = $request->input($mentorAgentInput);
                $startDate = $request->input("start_date_ment_".$i);
                $endDate = $request->input("end_date_ment_".$i);

                array_push($arrMentorAgents, $mentorAgentID);
                array_push($arrMentorAgentsDates, ["start_date" => $startDate, "end_date" => $endDate]);
                
            }

            $overrideAgentInput = "override_agent_" . $i;
            if ($request->has($overrideAgentInput) && !empty($request->input($overrideAgentInput))) {
                $overrideAgentID = $request->input($overrideAgentInput);
                $startDate = $request->input("start_date_over_".$i);
                $endDate = $request->input("end_date_over_".$i);

                array_push($arrOverrideAgents, $overrideAgentID);
                array_push($arrOverrideAgentsDates, ["start_date" => $startDate, "end_date" => $endDate]);
            }
        }


        $deleteMentorAgents = array_diff($mentorAgents, $arrMentorAgents);
        $deleteOverrideAgents = array_diff($overrideAgents, $arrOverrideAgents);

        
        AgentNumAgentModel::where("fk_agent_number_base", $agent_number->id)->whereIn("fk_agent_number_rel", $deleteMentorAgents)->delete();
        AgentNumAgentModel::where("fk_agent_number_base", $agent_number->id)->whereIn("fk_agent_number_rel", $deleteOverrideAgents)->delete();


        foreach ($arrMentorAgents as $row => $mentorAgentID) {
            $existMentorAgent = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)->where("fk_agent_number_rel", "=", $mentorAgentID)->first();
            if (!isset($existMentorAgent)) {
                $agentNumAgent = new AgentNumAgentModel();
                $agentNumAgent->type = 1;
                $agentNumAgent->fk_agent_number_base = $agent_number->id;                
                $agentNumAgent->fk_agent_number_rel = $mentorAgentID;
            }
            else{
                $agentNumAgent = AgentNumAgentModel::find($existMentorAgent->id);
            }

            $agentNumAgent->start_date = $arrMentorAgentsDates[$row]["start_date"];
            $agentNumAgent->end_date = $arrMentorAgentsDates[$row]["end_date"];
            
            $agentNumAgent->save();
        }

        foreach ($arrOverrideAgents as $row => $overrideAgentID) {
            $existOverrideAgent = AgentNumAgentModel::where("fk_agent_number_base", "=", $agent_number->id)->where("fk_agent_number_rel", "=", $overrideAgentID)->first();
            if (!isset($existOverrideAgent)) {
                $agentNumAgent = new AgentNumAgentModel();
                $agentNumAgent->type = 2;
                $agentNumAgent->fk_agent_number_base = $agent_number->id;
                $agentNumAgent->fk_agent_number_rel = $overrideAgentID;
            }
            else{
                $agentNumAgent = AgentNumAgentModel::find($existMentorAgent->id);
            }
            $agentNumAgent->start_date = $arrOverrideAgentsDates[$row]["start_date"];
            $agentNumAgent->end_date = $arrOverrideAgentsDates[$row]["end_date"];

            $agentNumAgent->save();
        }        
    }
}
