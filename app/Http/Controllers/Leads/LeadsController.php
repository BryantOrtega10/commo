<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leads\CreateLeadRequest;
use App\Http\Requests\Leads\UpdateDetailsRequest;
use App\Http\Requests\Leads\UpdateLeadRequest;
use App\Models\Agents\AgentsModel;
use App\Models\Customers\ActivitiesModel;
use App\Models\Customers\ActivityLogsModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\LegalBasisModel;
use App\Models\MultiTable\MaritalStatusModel;
use App\Models\MultiTable\PhasesModel;
use App\Models\MultiTable\RegistrationSourcesModel;
use App\Models\MultiTable\SuffixesModel;
use App\Models\Policies\CountiesModel;
use App\Models\User;
use App\Notifications\AgentTasksNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;

class LeadsController extends Controller
{
    public function show(Request $request)
    {
        $agent_user = Auth::user();
        $agent = AgentsModel::where("fk_user","=",$agent_user->id)->first();
        $agentId = $agent->id ?? '0';


        $leads = CustomersModel::select("customers.*")->where("fk_agent","=",$agentId);
        if ($request->has("first_name") && !empty($request->input("first_name"))) {
            $leads = $leads->where("first_name", "LIKE", "%" . $request->input("first_name") . "%");
        }

        if ($request->has("last_name") && !empty($request->input("last_name"))) {
            $leads = $leads->where("last_name", "LIKE", "%" . $request->input("last_name") . "%");
        }
        
        if ($request->has("date_birth") && !empty($request->input("date_birth"))) {
            $leads = $leads->where("date_birth", "LIKE", "%" . $request->input("date_birth") . "%");
        }

        if ($request->has("email") && !empty($request->input("email"))) {
            $leads = $leads->where("email", "LIKE", "%" . $request->input("email") . "%");
        }

        if ($request->has("phone") && !empty($request->input("phone"))) {
            $leads = $leads->where("phone", "LIKE", "%" . $request->input("phone") . "%");
        }

        if ($request->has("status") && !empty($request->input("status"))) {
            $leads = $leads->where("fk_status", "=", $request->input("status"));
        }

        if ($request->has("phase") && !empty($request->input("phase"))) {
            $leads = $leads->where("fk_phase", "=", $request->input("phase"));
        }

        if ($request->has("legal_basis") && !empty($request->input("legal_basis"))) {
            $leads = $leads->where("fk_legal_basis", "=", $request->input("legal_basis"));
        }

        $leads = $leads->get();

        session()->flashInput($request->all());

        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        

        return view('leads.show', [
            "leads" => $leads,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
            'change_pass' => $agent_user->change_password
        ]);
    }

    public function datatableAjax(Request $request)
    {
        $agent_user = Auth::user();
        $agent = AgentsModel::where("fk_user","=",$agent_user->id)->first();
        $agentId = $agent->id ?? '0';


        $leads = CustomersModel::select("customers.*")
                    ->leftJoin("customer_status","customer_status.id","=","customers.fk_status")
                    ->where("fk_agent","=",$agentId);

        if ($request->has("first_name") && !empty($request->input("first_name"))) {
            $leads = $leads->where("first_name", "LIKE", "%" . $request->input("first_name") . "%");
        }

        if ($request->has("last_name") && !empty($request->input("last_name"))) {
            $leads = $leads->where("last_name", "LIKE", "%" . $request->input("last_name") . "%");
        }
        
        if ($request->has("date_birth") && !empty($request->input("date_birth"))) {
            $leads = $leads->where("date_birth", "LIKE", "%" . $request->input("date_birth") . "%");
        }

        if ($request->has("email") && !empty($request->input("email"))) {
            $leads = $leads->where("email", "LIKE", "%" . $request->input("email") . "%");
        }

        if ($request->has("phone") && !empty($request->input("phone"))) {
            $leads = $leads->where("phone", "LIKE", "%" . $request->input("phone") . "%");
        }

        if ($request->has("status") && !empty($request->input("status"))) {
            $leads = $leads->where("fk_status", "=", $request->input("status"));
        }

        if ($request->has("phase") && !empty($request->input("phase"))) {
            $leads = $leads->where("fk_phase", "=", $request->input("phase"));
        }

        if ($request->has("legal_basis") && !empty($request->input("legal_basis"))) {
            $leads = $leads->where("fk_legal_basis", "=", $request->input("legal_basis"));
        }

        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $leads->where(function($query) use ($searchTxt) {
                $query->where("first_name","like","%{$searchTxt}%")
                      ->orWhere("last_name","like","%{$searchTxt}%")
                      ->orWhere("email","like","%{$searchTxt}%")
                      ->orWhere("phone","like","%{$searchTxt}%")
                      ->orWhere("customer_status.name","like","%{$searchTxt}%");
            });
        }

        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $leads->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '1':
                    $leads->orderBy("email",$direction);
                    break;
                case '2':
                    $leads->orderBy("phone",$direction);
                    break;
                case '3':
                    $leads->orderBy("customer_status.name",$direction);
                    break;
            }
        }
        
        $totalRecords = $leads->count();
        $leads = $leads->skip($request->input('start'))
                            ->take($request->input('length'))
                            ->get();

        $filteredRecords = array();

        foreach($leads as $lead){            
            $filteredRecord = array();
            $filteredRecord["lead"]["text"] = $lead->first_name." ".$lead->last_name;
            $filteredRecord["lead"]["href"] = route('leads.details',['id' => $lead->id]);
            $filteredRecord["email"] = $lead->email;
            $filteredRecord["phone"] = $lead->phone;
            $filteredRecord["status"] = $lead->status?->name;
            $filteredRecord["actions"]["href"] = route('leads.details',['id' => $lead->id]);
            array_push($filteredRecords, $filteredRecord);
        }
       

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);

        
    }

    public function showCreateForm(){
    
        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('leads.create', [
            "registration_sources" => $registration_sources,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
        ]);
    }

    public function create(CreateLeadRequest $request)
    {
        $entry_user = Auth::user();

        $agent = AgentsModel::where("fk_user","=",$entry_user->id)->first();
        $agentId = $agent->id ?? null;


        $lead = new CustomersModel();
        $lead->fk_business_type = 1; // 1 - Individual, 2 - Group
        $lead->first_name = $request->input('first_name');
        $lead->last_name = $request->input('last_name');
        $lead->date_birth = $request->input('date_birth');
        $lead->ssn = $request->input('ssn');
        $lead->email = $request->input('email');
        $lead->phone = $request->input('phone');        
        $lead->fk_registration_s = $request->input('registration_source');
        $lead->fk_customer = $request->input('referring_customer_id');
        $lead->fk_status = $request->input("status");
        $lead->fk_phase = $request->input("phase");
        $lead->fk_legal_basis = $request->input("legal_basis");
        $lead->fk_agent = $agentId; 
        $lead->fk_entry_user = $entry_user->id;
        $lead->save();

        return redirect(route('leads.show'))->with('message', 'Lead created successfully');
    }

    public function showDetailsForm($id, Request $request){

        $user = Auth::user();
        $user = User::find($user->id);
        $lead = CustomersModel::find($id);
        $activityLogs = ActivityLogsModel::whereHas("activity", function($query) use($id) {
                                                $query->where("fk_customer","=",$id);
                                           })
                                           ->orderBy("updated_at","DESC")->get();

        $tasks = ActivitiesModel::where("fk_customer","=",$id)
                                ->whereNotNull("task_name")
                                ->where("isDone","=",false)
                                ->orderBy("expiration_date","DESC")
                                ->get();

        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        
        $errors = session('errors', new ViewErrorBag);
        $typeActivity = null;
        if ($errors->addActivityForm->any()) {
            if ($errors->hasBag('addActivityForm')) {
                $typeActivity = session()->getOldInput('type');
            }
        }

       
        $activitySelected = null;
        if ($errors->detailsActivityForm->any()) {
            if ($errors->hasBag('detailsActivityForm')) {
                $activityId = session()->getOldInput('activityId');
                $activitySelected = ActivitiesModel::find($activityId);
            }
        }
        if($request->has("idActivity")){
            $activityId = $request->input("idActivity");
            $activitySelected = ActivitiesModel::find($activityId);
            //Change notification state

            $user->unreadNotifications()
            ->where('type', AgentTasksNotification::class)
            ->where('data->activity_id', $activitySelected->id)
            ->get() 
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        }
        


        return view('leads.details', [
            "lead" => $lead,
            "activityLogs" => $activityLogs,
            "tasks" => $tasks,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
            'typeActivity' => $typeActivity,
            'activitySelected' => $activitySelected
        ]);

    }

    public function updateDetails($id, UpdateDetailsRequest $request){

        $lead = CustomersModel::find($id);
        $lead->fk_status = $request->input("status");
        $lead->fk_phase = $request->input("phase");
        $lead->fk_legal_basis = $request->input("legal_basis");
        $lead->save();
             
        return redirect(route('leads.details',['id' => $id]))->with('message', 'Lead updated successfully');
    }

    public function showUpdateForm($id){

        $lead = CustomersModel::find($id);
        $matiral_statuses = MaritalStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('leads.update', [
            'lead' => $lead,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m
        ]);
    }

    public function update($id, UpdateLeadRequest $request)
    {

        $lead = CustomersModel::find($id);
        $lead->first_name = $request->input('first_name');
        $lead->middle_initial = $request->input('middle_initial');
        $lead->last_name = $request->input('last_name');
        $lead->fk_suffix = $request->input('suffix');
        $lead->date_birth = $request->input('date_birth');
        $lead->ssn = $request->input('ssn');
        $lead->fk_gender = $request->input('gender');
        $lead->fk_marital_status = $request->input('matiral_status');
        $lead->email = $request->input('email');
        $lead->address = $request->input('address');
        $lead->address_2 = $request->input('address_2');
        $lead->fk_county = $request->input('county');
        $lead->city = $request->input('city');
        $lead->zip_code = $request->input('zip_code');
        $lead->phone = $request->input('phone');
        $lead->phone_2 = $request->input('phone_2');
        $lead->fk_registration_s = $request->input('registration_source');
        $lead->fk_customer = $request->input('referring_customer_id');
        $lead->fk_status = $request->input("status");
        $lead->fk_phase = $request->input("phase");
        $lead->fk_legal_basis = $request->input("legal_basis");
        
        $lead->save();

        return redirect(route('leads.details',['id' => $id]))->with('message', 'Lead updated successfully');
    }
    
}
