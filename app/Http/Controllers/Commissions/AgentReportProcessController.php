<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsModel;
use App\Models\Utils\EmailTemplateModel;
use Illuminate\Http\Request;

class AgentReportProcessController extends Controller
{
    public function showAgentReportProcesses()
    {
        $agents = AgentsModel::orderBy("last_name", "ASC")->orderBy("first_name", "ASC")->get();

        return view('commissions.showAgentReportsProcesses', [
            "agents" => $agents
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
        $statements = StatementsModel::where("statement_date", "=", $request->input('statement_date'));
        
        $affected = $statements->count();

        $statements->update(["status" => "1"]);
        //TODO: Enviar mails
        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date');
        }
        else{
            return redirect(route('commissions.agent-process.show'))->with('message', 'Emails are being sent');
        }
    }
    public function sendMailIndividual(Request $request)
    {
        $statements = StatementsModel::where("statement_date", "=", $request->input('statement_date'))
            ->whereHas("agent_number", function ($query) use ($request) {
                $query->whereIn("fk_agent", $request->input("selectedAgent", []));
            });
        $affected = $statements->count();

        $statements->update(["status" => "1"]);
        //TODO: Enviar mails

        if ($affected == 0) {
            return redirect(route('commissions.agent-process.show'))->with('error', 'No statements were found for this date and agent');
        }
        else{
            return redirect(route('commissions.agent-process.show'))->with('message', 'Emails are being sent');
        }
    }
}
