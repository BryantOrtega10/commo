<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsItemModel;
use App\Models\Commissions\StatementsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MySettlementsController extends Controller
{
    public function show(Request $request){

        $agent_user = Auth::user();
   
        $settlements = StatementsModel::join("agent_numbers","agent_numbers.id","=","statements.fk_agent_number")
                                      ->join("agents","agents.id","=","agent_numbers.fk_agent")
                                      ->where("agents.fk_user","=",$agent_user->id)
                                      ->where("statements.status","=","1");

        if ($request->has("start_date") && !empty($request->input("start_date"))) {
            $settlements->where("statement_date", ">=", $request->input("start_date"));
        }
        if ($request->has("end_date") && !empty($request->input("end_date"))) {
            $settlements->where("statement_date", "<=", $request->input("end_date"));
        }


        $settlements = $settlements->get();
        session()->flashInput($request->all());

        return view('leads.mySettlements',[
            'settlements' => $settlements
        ]);
    }

    public function generatePDF($id){
        $agent_user = Auth::user();
        $statement = StatementsModel::find($id);
        if(!isset($statement) || $agent_user->id !== $statement->agent_number->agent->fk_user){
            return redirect(route('my-settlements.show'))->with('error',"This statement does not belong to you");
        }

        $statementItems = StatementsItemModel::where("fk_statement", "=", $id);
        $statementItems = $statementItems->get();

        $arrAdjustCompType = ["Prior Balance", "Adjustment"];


        $finalData = [];
        foreach ($statementItems as $statementItem) {
            $agentID = $statementItem->statement->agent_number->fk_agent;
            $agentNumberID = $statementItem->statement->fk_agent_number;
            $agentName = $statementItem->statement->agent_number->agent->first_name . " " . $statementItem->statement->agent_number->agent->last_name;
            $transaction = $statementItem->commission_transaction;


            $finalData[$agentID]['name'] = $agentName;
            $finalData[$agentID]['ammount_adjustments'] = $finalData[$agentID]['ammount_adjustments'] ?? 0;

            $finalData[$agentID]['agentNumbers'][$agentNumberID]['carrier'] = $statementItem->statement->agent_number?->carrier?->name ?? "";
            $finalData[$agentID]['agentNumbers'][$agentNumberID]['status'] = $statementItem->statement->agent_number?->agent_status?->name ?? "";
            $finalData[$agentID]['agentNumbers'][$agentNumberID]['number'] = $statementItem->statement->agent_number->number ?? "";

            if (!in_array($transaction->compensation_type?->name, $arrAdjustCompType)) {
                $finalData[$agentID]['agentNumbers'][$agentNumberID]['commission_amount'] = $finalData[$agentID]['agentNumbers'][$agentNumberID]['commission_amount'] ?? 0;
                $finalData[$agentID]['agentNumbers'][$agentNumberID]['commission_amount'] += $statementItem->comp_amount;
                $finalData[$agentID]['agentNumbers'][$agentNumberID]['statements_items'][] = $statementItem;
            } else {
                $finalData[$agentID]['ammount_adjustments'] += $statementItem->comp_amount;
                $finalData[$agentID]['adjustments'][$statementItem->statement->agent_number->number][$transaction->compensation_type?->name][$transaction->adjusment_subscriber] = $finalData[$agentID]['adjustments'][$statementItem->statement->agent_number->number][$transaction->compensation_type?->name][$transaction->adjusment_subscriber] ?? 0;
                $finalData[$agentID]['adjustments'][$statementItem->statement->agent_number->number][$transaction->compensation_type?->name][$transaction->adjusment_subscriber] += $statementItem->comp_amount;
            }
        }

        $pdf = Pdf::loadView('commissions.pdf.multiAgentStatementsReport', [
            'statement_date' => $statement->statement_date,
            'finalData' => $finalData
        ])->setPaper('A4', 'landscape');

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        // Agregar número de página manualmente
        $canvas = $dompdf->get_canvas();
        $canvas->page_text(15, 540, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);


        return $pdf->download('Statement ' . date("m-d-Y",strtotime($statement->statement_date)) . ' - '.$id.'.pdf'); // O ->stream('archivo.pdf');
    }
}
