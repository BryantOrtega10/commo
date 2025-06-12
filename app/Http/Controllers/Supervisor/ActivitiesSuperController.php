<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Leads\CreateActivityRequest;
use App\Http\Requests\Leads\UpdateActivityRequest;
use App\Mail\ActivitiesMail;
use App\Models\Customers\ActivitiesModel;
use App\Models\Customers\ActivityLogsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ActivitiesSuperController extends Controller
{
    public function showActivityModal($idLead, $type){

        Utils::createLog(
            "The user entered the activity form for lead ID: ".$idLead,
            "leads.activity",
            "show"
        );
        
        return view('supervisor_activities.partials.activityModal',[
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

        $mailSent = false;
        $mailError = "";
        if ($activity->type == 2) {
            $mailText = $activity->description;
            if (filter_var($activity->customer?->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($activity->customer?->email)->send(new ActivitiesMail($mailText));
                    $mailSent = true;
                } catch (\Exception $e) {
                    $mailError = $e->getMessage();
                }
            } else {
                $mailError = "The lead's email is not valid";
            }
        }
        
        $logMessage = "";
        if($activity->type <> 2){
            $logMessage = "A ".$activity->txt_type." has been created";
        }
        else {
            $logMessage = "An email has been created";
            if ($mailSent) {
                $logMessage .= " and sent";
            } else {
                $logMessage .= ", but the email could not be sent for this reason: " . $mailError;
            }
        }
        
        $entry_user = Auth::user();

        $log = new ActivityLogsModel();
        $log->description = $logMessage;
        $log->fk_activity = $activity->id;
        $log->fk_entry_user = $entry_user->id;
        $log->save();

        
        Utils::createLog(
            "The user has created a new activity with ID: ".$activity->id." to lead with ID: ".$idLead,
            "leads.activity",
            "create"
        );

        if ($activity->type == 2 && !$mailSent) {
            return redirect(route('supervisor.leads.details', ['id' => $idLead]))->with('error', "Activity created successfully, but the email could not be sent for this reason: " . $mailError);
        }

        return redirect(route('supervisor.leads.details',['id' => $idLead]))->with('message', 'Activity created successfully');
    }

    public function showActivityDetailsModal($id){

        $activity = ActivitiesModel::find($id);
        
        Utils::createLog(
            "The user entered the activity details for activity ID: ".$id,
            "leads.activity",
            "show"
        );

        return view('supervisor_activities.partials.activityDetailsModal',[
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

        if ($request->input("resendMail") == "1") {
            $mailError = "";
            if ($activity->type == 2) {
                $mailText = $activity->description;
                if (filter_var($activity->customer?->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($activity->customer?->email)->send(new ActivitiesMail($mailText));
                        $mailSent = true;
                    } catch (\Exception $e) {
                        $mailError = $e->getMessage();
                    }
                } else {
                    $mailError = "The lead's email is not valid";
                }
            }
            if ($mailSent) {
                $logMessage .= ", the email was forwarded";
            } else {
                $logMessage .= ", an attempt was made to resend the email but it could not be sent for this reason: " . $mailError;
            }
        }

        $entry_user = Auth::user();

        $log = new ActivityLogsModel();
        $log->description = $logMessage;
        $log->fk_activity = $activity->id;
        $log->fk_entry_user = $entry_user->id;
        $log->save();

        if ($activity->type == 2 && $request->input("resendMail") == "1" && !$mailSent) {
            return redirect(route('leads.details', ['id' => $activity->fk_customer]))->with('error', "Activity updated successfully, but the email could not be sent for this reason: " . $mailError);
        }

        Utils::createLog(
            "The user has updated the activity with ID: ".$id,
            "leads.activity",
            "update"
        );
        

        return redirect(route('supervisor.leads.details',['id' => $activity->fk_customer]))->with('message', 'Activity updated successfully');
    }

    public function myNotifications(){
        $user = Auth::user();
        $notifications = User::find($user->id)->unreadNotifications;
      
        return response()->json($notifications);
    }

    
}
