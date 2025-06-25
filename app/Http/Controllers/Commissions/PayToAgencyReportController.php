<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\PayToAgencyReport\PayToAgencyReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Commissions\StatementsItemModel;
use App\Models\MultiTable\AgenciesModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PayToAgencyReportController extends Controller
{
    public function show()
    {

        Utils::createLog(
            "The user has entered the pay to agency report.",
            "commissions.pay-to-agency",
            "show"
        );

        $agencies = AgenciesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('commissions.showPayToAgency', [
            "agencies" => $agencies
        ]);
    }

    public function generateReport(Request $request)
    {

        $agency = AgenciesModel::find($request->input("agency"));

        $statementItems = StatementsItemModel::select("statement_items.*")
            ->join("statements", "statements.id", "=", "statement_items.fk_statement")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->join("agents", "agents.id", "=", "agent_numbers.fk_agent")
            ->where("statements.statement_date", "=", $request->input("statement_date"))
            ->where("statement_items.agent_type", "=", "0"); //Writting Agent

        if ($request->has("agency") && !empty($request->input("agency"))) {
            $input = $request->input("agency");
            $statementItems->where('agent_numbers.fk_agency', "=", $input);
        }

        if ($statementItems->count() == 0) {
            return redirect(route('commissions.pay-to-agency.show'))->with('error', 'No statements were found for this search');
        }

        $statementItems->orderBy("agent_numbers.number", "ASC");
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

        Utils::createLog(
            "The user has created a pay to agency report",
            "commissions.pay-to-agency",
            "create"
        );

        if ($request->input("export_type") === "pdf") {
            $pdf = Pdf::loadView('commissions.pdf.payToAgencyReport', [
                'agency_name' => $agency->name,
                'statement_date' => $request->input("statement_date"),
                'finalData' => $finalData
            ])->setPaper('A4', 'landscape');

            $dompdf = $pdf->getDomPDF();
            $dompdf->render();

            $canvas = $dompdf->get_canvas();
            $canvas->page_text(15, 540, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);
            return $pdf->download('PayToAgencyReport ' . $request->input("statement_date") . '.pdf'); // ->stream('archivo.pdf'); 
        } else {

            $summaryData = ["items" => []];
            $subTotalTransaction = 0;
            $subTotal = 0;
            $adjustments = 0;

            $detailsData = [];

            $adjustmentsData = [];

            foreach ($finalData as $row => $item) {
                foreach ($item['agentNumbers'] as $agentNumberId => $agentNumberItem) {

                    array_push($detailsData, [
                        "carrier" => $agentNumberItem['carrier'],
                        "number" => $agentNumberItem['number'],
                        "name" => $item['name'],
                        "items" => $agentNumberItem['statements_items']
                    ]);

                    array_push($summaryData["items"], [
                        "carrier" => $agentNumberItem['carrier'],
                        "number_status" => $agentNumberItem['number'] . " - " . $agentNumberItem['status'],
                        "transactions_count" => sizeof($agentNumberItem['statements_items'] ?? []),
                        "commission_amount" => $agentNumberItem['commission_amount']
                    ]);
                    $subTotalTransaction += sizeof($agentNumberItem['statements_items'] ?? []);
                    $subTotal += $agentNumberItem['commission_amount'] ?? 0;
                }

                if (isset($item['adjustments'])) {
                    foreach ($item['adjustments'] as $agentNumber => $itemAdjustment) {
                        foreach ($itemAdjustment as $compensation_type => $itemAdjustment2) {
                            foreach ($itemAdjustment2 as $description => $comp_amount) {
                                array_push($adjustmentsData, [
                                    "agentNumber" => $agentNumber,
                                    "compensation_type" => $compensation_type,
                                    "description" => $description,
                                    "comp_amount" => $comp_amount
                                ]);
                            }
                        }
                    }
                }

                $adjustments += ($item['ammount_adjustments'] ?? 0);
            }

            $summaryData["summary"] = [
                "subTotalTransaction" => $subTotalTransaction,
                "subTotal" => $subTotal,
                "adjustments" => $adjustments,
                "total" => $subTotal + $adjustments,
            ];

            return Excel::download(new PayToAgencyReportExport($summaryData, $detailsData, $adjustmentsData), 'PayToAgencyReport ' . $request->input("statement_date") . '.xlsx');
        }
    }
}
