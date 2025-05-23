<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leads\CreateActivityRequest;
use App\Http\Requests\Leads\UpdateActivityRequest;
use App\Models\Customers\ActivitiesModel;
use App\Models\Customers\ActivityLogsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivitiesController extends Controller
{
    public function showActivityModal($idLead, $type){
        return view('activities.partials.activityModal',[
            'type' => $type,
            'idLead' => $idLead
        ]);
    }

    public function createActivity($idLead, CreateActivityRequest $request){

        $activity = new ActivitiesModel();
        $activity->type = $request->input("type");
        $activity->description = $request->input("html_desc");
        if($request->has("create_task") || $request->input("type") == 5){
            $activity->task_name = $request->input("task_name");
            $activity->expiration_date = $request->input("expiration_date")." ".$request->input("expiration_hour");
            $activity->priority = $request->input("priority");
        }
        $activity->fk_customer = $idLead;
        $activity->isDone = false;
        $activity->save();

        //TODO: Enviar correo
        $logMessage = "";
        if($activity->type <> 2){
            $logMessage = "A ".$activity->txt_type." has been created";
        }
        else{
            $logMessage = "An email was sent";
        }
        
        $entry_user = Auth::user();

        $log = new ActivityLogsModel();
        $log->description = $logMessage;
        $log->fk_activity = $activity->id;
        $log->fk_entry_user = $entry_user->id;
        $log->save();

        return redirect(route('leads.details',['id' => $idLead]))->with('message', 'Activity created successfully');
    }

    public function showActivityDetailsModal($id){

        $activity = ActivitiesModel::find($id);
        
        return view('activities.partials.activityDetailsModal',[
            'activity' => $activity
        ]);
    }
    
    public function update($id, UpdateActivityRequest $request){
        $activity = ActivitiesModel::find($id);
        $logMessage = "";
        if($activity->isDone != $request->input("isDone")){
            $activity->isDone = $request->input("isDone");
            $logMessage .= $activity->txt_type." was changed to ".$activity->txt_done;
        }
        else{
            $logMessage .= "A ".$activity->txt_type." has been updated";
        }
        $activity->description = $request->input("html_desc");
        if($request->has("create_task") || $request->input("type") == 5){
            $activity->task_name = $request->input("task_name");
            $activity->expiration_date = $request->input("expiration_date")." ".$request->input("expiration_hour");
            $activity->priority = $request->input("priority");
        }
        $activity->save();

        $entry_user = Auth::user();

        $log = new ActivityLogsModel();
        $log->description = $logMessage;
        $log->fk_activity = $activity->id;
        $log->fk_entry_user = $entry_user->id;
        $log->save();

        //TODO: Enviar Mail si se dio reenviar

        return redirect(route('leads.details',['id' => $activity->fk_customer]))->with('message', 'Activity updated successfully');
    }

    public function myNotifications(){
        $user = Auth::user();
        $notifications = User::find($user->id)->unreadNotifications;
      
        return response()->json($notifications);
    }

    
}
