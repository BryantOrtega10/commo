<?php

namespace App\Jobs;

use App\Models\Commissions\StatementsItemModel;
use App\Models\Commissions\StatementsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class SendAgentReportMailJob implements ShouldQueue
{
    use Queueable, SerializesModels, Batchable;

    protected $idStatement;

    /**
     * Create a new job instance.
     */
    public function __construct($idStatement)
    {
        $this->idStatement = $idStatement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $statement = StatementsModel::find($this->idStatement);
        $statementItems = StatementsItemModel::where("fk_statement", "=", $this->idStatement);
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
            $finalData[$agentID]['agentNumbers'][$agentNumberID]['pay_agency'] = $statementItem->statement->agent_number->agency?->name ?? "";
            
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

        $generatedPdf = $pdf->output();
        
        //TODO: Enviar Mail
        
    }
}
