<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsItemModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReferralReportController extends Controller
{
    public function show()
    {

        Utils::createLog(
            "The user has entered to referral report.",
            "commissions.referral",
            "show"
        );

        $agents = AgentsModel::orderBy("last_name", "ASC")->orderBy("first_name", "ASC")->get();

        return view('commissions.showReferral', [
            "agents" => $agents
        ]);
    }

    public function generateReport(Request $request)
    {
        $agent = AgentsModel::find($request->input('agent'));

        $statementItems = StatementsItemModel::select("statement_items.*")
            ->join("statements", "statements.id", "=", "statement_items.fk_statement")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->join("agents", "agents.id", "=", "agent_numbers.fk_agent")
            ->where("statements.statement_date", "=", $request->input("statement_date"))
            ->where("statement_items.agent_type", "<>", "0") //Que no sea Writting Agent
            ->where("agent_numbers.fk_agent","=",$request->input('agent')); 
                

        if ($statementItems->count() == 0) {
            return redirect(route('commissions.referral.show'))->with('error', 'No statements were found for this search');
        }

        $statementItems->orderBy("statement_items.agent_type", "ASC");
        $statementItems->orderBy("agent_numbers.number", "ASC");
        $statementItems = $statementItems->get();

        $summary = ["items" => [], "transaction_count" => 0, "commission_amount" => 0];
        $details = [];

        foreach($statementItems as $statementItem){
            $policy = $statementItem->commission_transaction->policy;
            if(!isset($policy)){
                //Pregunta: Que pasa con los ajustes?
                continue;
            }
            
            $writtingAgentNum = $policy->agent_number;

            $agentNumberID = $writtingAgentNum->id;
            $agentName = ucfirst(substr($writtingAgentNum->agent->first_name, 0, 1)) . " " . ucfirst(strtolower($writtingAgentNum->agent->last_name));
            $commTypeId = $statementItem->commission_transaction->fk_compensation_type;
            $commTypeName = $statementItem->commission_transaction->compensation_type?->name ?? "";
            

            $summary["items"][$agentNumberID][$commTypeId]['agent_type'] = $statementItem->txt_agent_type;
            $summary["items"][$agentNumberID][$commTypeId]['carrier'] = $statementItem->statement->agent_number?->carrier?->name ?? "";
            $summary["items"][$agentNumberID][$commTypeId]['name'] = $agentName;
            $summary["items"][$agentNumberID][$commTypeId]['comm_type'] = $commTypeName;
            $summary["items"][$agentNumberID][$commTypeId]['transaction_count'] = ($summary[$agentNumberID][$commTypeId]['transaction_count'] ?? 0) + 1;
            $summary["items"][$agentNumberID][$commTypeId]['commission_amount'] = ($summary[$agentNumberID][$commTypeId]['commission_amount'] ?? 0) + $statementItem->comp_amount;
            $summary["transaction_count"]++;
            $summary["commission_amount"] += $statementItem->comp_amount;


            array_push($details, [
                "subscriber" => $policy->customer->first_name." ".$policy->customer->last_name,
                "prod_type" => $policy->product?->product_type?->name, // Prod Type
                "plan" => $policy->product?->description, // Plan Description
                "n_members" => $policy->num_dependents, // # Of Members
                "policy_num" => $policy->contract_id, // # Of Members
                "orig_eff" => $policy->original_effective_date, // Orig Eff
                "account_date" => $statementItem->commission_transaction->accounting_date, // Account Date
                "cancel_date" => $policy->cancel_date, // Cancel Date
                "tier" => $policy->product?->tier?->name, // Tier
                "region" => $policy->county?->region?->name, // Region
                "writting_agent" => $agentName, // Writting Agent
                "agent_commision" => $statementItem->comp_amount, // Agent Commission
            ]);
        }

         Utils::createLog(
            "The user has created a referral report",
            "commissions.referral",
            "create"
        );

        $pdf = Pdf::loadView('commissions.pdf.referralReport', [
            'agent' => $agent,
            'details' => $details,
            'summary' => $summary,
            'statement_date' => $request->input("statement_date"),
        ])->setPaper('A4', 'landscape');

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        $canvas = $dompdf->get_canvas();
        $canvas->page_text(15, 540, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);
        return $pdf->stream('archivo.pdf'); // ->download('Referral Report ' . $request->input("statement_date") . '.pdf'); 

        
    }
}

