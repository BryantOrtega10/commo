<?php


namespace App\Services\Reports;

use App\Exports\AgentBatchReportExport;
use App\Models\Commissions\StatementsItemModel;
use App\Models\Reports\AgentBatchReportItemModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AgentReportProcessor
{
    public function process($reportItemId)
    {
        $reportItem = AgentBatchReportItemModel::find($reportItemId);
        $report = $reportItem->report;
        $statement = $reportItem->statement;

        if ($report->export_file_type === 0) {

            $statementItems = StatementsItemModel::where("fk_statement", "=", $statement->id);
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

            $folder = 'batchReport' . $report->id;
            $filename = 'report_item_' . $reportItem->id . '.pdf';
            $path = $folder . '/' . $filename;
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }
            Storage::disk('public')->put($path, $pdf->output());
        }
        else{
            $folder = 'batchReport' . $report->id;
            $filename = 'report_item_' . $reportItem->id . '.xlsx';
            $path = $folder . '/' . $filename;
            if (!Storage::disk('public')->exists($folder)) {

                Storage::disk('public')->makeDirectory($folder);
            }
            Excel::store(new AgentBatchReportExport($statement->id), $path, 'public');

        }
        $reportItem = AgentBatchReportItemModel::find($reportItemId);
        $reportItem->status = 1;
        $reportItem->save();
        
        $report->increment('generated',1);
        $report->save();
    }
}
