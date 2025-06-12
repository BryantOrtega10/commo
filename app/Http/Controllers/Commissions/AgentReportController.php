<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsItemModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentTitlesModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AgentReportController extends Controller
{
    public function showAgentReport()
    {
        $agent_titles = AgentTitlesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('commissions.showAgentReport', [
            "agency_codes" => $agency_codes,
            "agent_titles" => $agent_titles,
            "agencies" => $agencies
        ]);
    }

    public function generateAgentReport(Request $request)
    {
        $statementItems = StatementsItemModel::select("statement_items.*")
            ->join("statements", "statements.id", "=", "statement_items.fk_statement")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->join("agents", "agents.id", "=", "agent_numbers.fk_agent")
            ->where("statements.statement_date", "=", $request->input("statement_date"));
        if (!$request->has("all_agents")) {
            $statementItems->whereIn("statements.fk_agent_number", $request->input("agentNumberID"));
        }

        if ($request->has("agent_title") && !empty($request->input("agent_title"))) {
            $input = $request->input("agent_title");
            $statementItems->where('agent_numbers.fk_agent_title', "=", $input);
        }
        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $statementItems->where('agent_numbers.fk_agency_code', "=", $input);
        }

        

        if ($statementItems->count() == 0) {
            return redirect(route('commissions.agent-report.show'))->with('error', 'No statements were found for this search');
        }
        $statementItems->orderBy("agents.last_name", "ASC");
        $statementItems->orderBy("agents.first_name", "ASC");
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
            'statement_date' => $request->input("statement_date"),
            'finalData' => $finalData
        ])
            ->setPaper('A4', 'landscape');

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        // Agregar número de página manualmente
        $canvas = $dompdf->get_canvas();
        $canvas->page_text(15, 540, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);

        Utils::createLog(
            "The user has created a agent report",
            "commissions.agent-report",
            "create"
        );

        return $pdf->download('AgentReport' . date("Y-m-d") . '.pdf'); // O ->stream('archivo.pdf');
    }

    public function dataTableAgentReport(Request $request)
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
}
