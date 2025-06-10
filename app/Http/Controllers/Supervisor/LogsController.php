<?php

namespace App\Http\Controllers\Supervisor;

use App\Exports\LogExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LogsController extends Controller
{
    public function show()
    {
        Utils::createLog(
            "The user entered the users list from log.",
            "logs",
            "show"
        );

        return view('supervisor_logs.show');
    }

    public function datatableAjax(Request $request)
    {
        $agents = AgentsModel::select("agents.*")->join("users", "users.id", "=", "agents.fk_user")
                                                 ->where("users.role", "=", "agent");
        
        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $agents->where(function ($query) use ($searchTxt) {
                $query->where("users.name", "like", "%{$searchTxt}%")
                    ->orWhere("users.email", "like", "%{$searchTxt}%")
                    ->orWhere("users.role", "like", "%{$searchTxt}%")
                    ->orWhere("agents.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.email", "like", "%{$searchTxt}%")
                    ->orWhere("agents.phone", "like", "%{$searchTxt}%");
            });
        }
        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $agents->orderBy("agents.id", $direction);
                    break;
                case '1':
                    $agents->orderByRaw("CONCAT(agents.first_name,' ',agents.last_name) $direction");
                    break;
                case '2':
                    $agents->orderBy("users.email", $direction);
                    break;
                case '3':
                    $agents->orderBy("users.status", $direction);
                    break;
            }
        }

        $totalRecords = $agents->count();
        $agents = $agents->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($agents as $agent) {
            $filteredRecord = array();
            $filteredRecord["id"] = "$agent->fk_user";
            $filteredRecord["name"] = "$agent->first_name $agent->last_name";
            $filteredRecord["email"] = $agent->user->email;
            $filteredRecord["status"]["number"] = $agent->user->status;
            $filteredRecord["status"]["text"] = $agent->user->txt_status;
            $filteredRecord["actions"]["log"] = route('supervisor-logs.log', ['id' => $agent->fk_user]);

            array_push($filteredRecords, $filteredRecord);
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);
    }

    public function showLogForm($id)
    {

        Utils::createLog(
            "The user has entered the user log to ID: " . $id,
            "logs",
            "show"
        );

        return view('supervisor_logs.log', [
            'userID' => $id
        ]);
    }

    public function downloadLog($id, Request $request)
    {

        Utils::createLog(
            "The user has downloaded the Log report for user ID: " . $id,
            "logs",
            "create"
        );

        return Excel::download(new LogExport($id, $request->input("start_date"), $request->input("end_date")), 'Log Report ' . $id . '.xlsx');
    }
}
