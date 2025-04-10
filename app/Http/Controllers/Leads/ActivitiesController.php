<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leads\CreateActivityRequest;
use App\Models\Customers\ActivitiesModel;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function showActivityModal($idLead, $type){
        return view('leads.partials.activityModal',[
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

        return redirect(route('leads.details',['id' => $idLead]))->with('message', 'Activity created successfully');
    }
}
