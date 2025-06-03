<?php

namespace App\Http\Controllers\Commissions;

use App\Exports\AgentBatchReportExport;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessAgentReportJob;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\Reports\AgentBatchReportItemModel;
use App\Models\Reports\AgentBatchReportModel;
use App\Models\Utils\EmailTemplateModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class AgentReportProcessController extends Controller
{
    public function showAgentReportProcesses()
    {
        $agents = AgentsModel::orderBy("last_name", "ASC")->orderBy("first_name", "ASC")->get();
        $reports = AgentBatchReportModel::all();
        $agency_codes = AgencyCodesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('commissions.showAgentReportsProcesses', [
            "agents" => $agents,
            'reports' => $reports,
            "agency_codes" => $agency_codes
        ]);
    }

    public function showAgentReportEmailTemplate()
    {

        $template = EmailTemplateModel::find(1);

        return view('commissions.partials.showEmailTemplateModal', [
            "template" => $template
        ]);
    }

    public function updateEmailTemplate(Request $request)
    {
        $template = EmailTemplateModel::find(1);
        $template->description = $request->input("html_desc");
        $template->save();

        return redirect(route('commissions.agent-process.show'))->with('message', 'Email Template updated successfully');
    }


    public function sendMailBatch(Request $request)
    {
        $statements = StatementsModel::select("statements.*")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->where("statements.statement_date", "=", $request->input('statement_date'));

        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $statements->where('agent_numbers.fk_agency_code', "=", $input);
        }

        $affected = $statements->count();

        $statements->update(["status" => "1"]);
        //TODO: Enviar mails
        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date');
        } else {
            return redirect(route('commissions.agent-process.show'))->with('message', 'Emails are being sent');
        }
    }
    public function sendMailIndividual(Request $request)
    {
        $statements = StatementsModel::select("statements.*")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->where("statements.statement_date", "=", $request->input('statement_date'))
            ->whereHas("agent_number", function ($query) use ($request) {
                $query->whereIn("fk_agent", $request->input("selectedAgent", []));
            });

        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $statements->where('agent_numbers.fk_agency_code', "=", $input);
        }


        $affected = $statements->count();

        $statements->update(["status" => "1"]);
        //TODO: Enviar mails

        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date and agent');
        } else {
            return redirect(route('commissions.agent-process.show'))->with('message', 'Emails are being sent');
        }
    }

    public function generateAgentReportBatch(Request $request)
    {

        $entry_user = Auth::user();
        $statements = StatementsModel::select("statements.*")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->where("statements.statement_date", "=", $request->input('statement_date'));

        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $statements->where('agent_numbers.fk_agency_code', "=", $input);
        }

        $affected = $statements->count();
        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date');
        }
        $statements = $statements->get();

        $report = new AgentBatchReportModel();
        $report->statement_date = $request->input('statement_date');
        $report->export_file_type = $request->input('exportFile');
        $report->total = $affected;
        $report->fk_entry_user = $entry_user->id;
        $report->processing_start_date = date("Y-m-d H:i:s");
        $report->save();

        $jobs = [];
        foreach ($statements as $statement) {
            $itemReport = new AgentBatchReportItemModel();
            $itemReport->fk_statement = $statement->id;
            $itemReport->fk_report = $report->id;
            $itemReport->save();
            array_push($jobs, new ProcessAgentReportJob($itemReport->id));
        }
        $reportId = $report->id;
        Bus::batch($jobs)
            ->finally(function ($batch) use ($reportId) {
                $report = AgentBatchReportModel::find($reportId);

                $errorItems = AgentBatchReportItemModel::select("agent_batch_report_item.*")
                    ->where("fk_report", "=", $reportId)
                    ->where("status", "=", 0)
                    ->update(["status" => 1]);

                $report->processing_end_date = date("Y-m-d H:i:s");
                //$report->generated += $errorItems;
                $report->status = 1;
                $report->save();
            })
            ->dispatch();

        return redirect(route('commissions.agent-process.showUpload', ['id' => $report->id]))->with('message', 'Report files are being generated');
    }
    public function generateAgentReportIndividual(Request $request)
    {
        $entry_user = Auth::user();
        $statements = StatementsModel::select("statements.*")
            ->join("agent_numbers", "agent_numbers.id", "=", "statements.fk_agent_number")
            ->where("statements.statement_date", "=", $request->input('statement_date'))
            ->whereHas("agent_number", function ($query) use ($request) {
                $query->whereIn("fk_agent", $request->input("selectedAgent", []));
            });

        if ($request->has("agency_code") && !empty($request->input("agency_code"))) {
            $input = $request->input("agency_code");
            $statements->where('agent_numbers.fk_agency_code', "=", $input);
        }
        $affected = $statements->count();
        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date and agents');
        }
        $statements = $statements->get();

        $report = new AgentBatchReportModel();
        $report->statement_date = $request->input('statement_date');
        $report->export_file_type = $request->input('exportFile');
        $report->total = $affected;
        $report->fk_entry_user = $entry_user->id;
        $report->save();

        $jobs = [];
        foreach ($statements as $statement) {
            $itemReport = new AgentBatchReportItemModel();
            $itemReport->fk_statement = $statement->id;
            $itemReport->fk_report = $report->id;
            $itemReport->save();
            array_push($jobs, new ProcessAgentReportJob($itemReport->id));
        }
        $reportId = $report->id;
        Bus::batch($jobs)
            ->finally(function ($batch) use ($reportId) {
                $report = AgentBatchReportModel::find($reportId);

                $errorItems = AgentBatchReportItemModel::select("agent_batch_report_item.*")
                    ->where("fk_report", "=", $reportId)
                    ->where("status", "=", 0)
                    ->update(["status" => 1]);

                $report->processing_end_date = date("Y-m-d H:i:s");
                $report->generated += $errorItems;
                $report->status = 1;
                $report->save();
            })
            ->dispatch();

        return redirect(route('commissions.agent-process.showUpload', ['id' => $report->id]))->with('message', 'Report files are being generated');
    }

    public function showAgentReportProcessesBatch($id)
    {
        $report = AgentBatchReportModel::find($id);

        $generated = $report->generated * 100 / $report->total;
        $total = 100 - $generated;

        $percentageGenerated = round($generated, 2);
        $percentageTotal = round($total, 2);

        return view('commissions.showAgentReportBatchProcess', [
            'report' => $report,
            'percentageGenerated' => $percentageGenerated,
            'percentageTotal' => $percentageTotal,
        ]);
    }

    public function showAgentReportProcessesBatchJson($id)
    {
        $report = AgentBatchReportModel::find($id);

        $generated = $report->generated * 100 / $report->total;
        $total = 100 - $generated;

        $percentageGenerated = round($generated, 2);
        $percentageTotal = round($total, 2);

        return response()->json([
            'status' => $report->status,
            'total' => $report->total,
            'generated' => $report->generated,
            'percentageGenerated' => $percentageGenerated,
            'percentageTotal' => $percentageTotal,
        ]);
    }

    public function download($id)
    {
        $report = AgentBatchReportModel::find($id);
        if ($report->status == 0) {
            return redirect(route('commissions.agent-process.showUpload', ['id' => $report->id]))->with('error', 'Report files are still being generated');
        }

        $folderName = 'batchReport' . $report->id;
        $storagePath = storage_path("app/public/{$folderName}");

        if (!File::exists($storagePath)) {
            return redirect(route('commissions.agent-process.showUpload', ['id' => $report->id]))->with('error', 'Folder not found');
        }

        $zipFileName = "{$folderName}.zip";
        $zipPath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles($storagePath);

            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }

            $zip->close();
        } else {
            return redirect(route('commissions.agent-process.showUpload', ['id' => $report->id]))->with('error', 'The ZIP file could not be created.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function delete($id)
    {
        $report = AgentBatchReportModel::find($id);

        $folderName = 'batchReport' . $report->id;
        $storagePath = storage_path("app/public/{$folderName}");

        if (File::exists($storagePath)) {
            Storage::disk('public')->deleteDirectory($folderName);
        }


        $report->delete();

        return redirect(route('commissions.agent-process.show'))->with('message', 'Report has been deleted');
    }

    public function testProcessor($id)
    {

        $reportItem = AgentBatchReportItemModel::find($id);
        $report = $reportItem->report;
        $statement = $reportItem->statement;

        $folder = 'batchReport' . $report->id;
        $filename = 'report_item_' . $reportItem->id . '.xlsx';
        $path = $folder . '/' . $filename;
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        Excel::store(new AgentBatchReportExport($statement->id), $path, 'public');
    }
}
