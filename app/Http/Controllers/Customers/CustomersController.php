<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Customers\CreateCustomerRequest;
use App\Models\Customers\CuidsModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\GendersModel;
use App\Models\MultiTable\LegalBasisModel;
use App\Models\MultiTable\MaritalStatusModel;
use App\Models\MultiTable\PhasesModel;
use App\Models\MultiTable\RegistrationSourcesModel;
use App\Models\MultiTable\SuffixesModel;
use App\Models\Policies\CountiesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ViewErrorBag;

class CustomersController extends Controller
{
    public function show(Request $request)
    {
        $customers = []; //Datatable        
        Utils::createLog(
            "The user has entered the customers list.",
            "customers.customers",
            "show"
        );
        session()->flashInput($request->all());

        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $matiral_statuses = MaritalStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view('customers.show', [
            "customers" => $customers,
            "business_types" => $business_types,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
        ]);
    }

    public function datatableAjax(Request $request)
    {
        $customers = CustomersModel::select("customers.*");

        if ($request->has("business_type") && !empty($request->input("business_type"))) {
            $customers->where("fk_business_type", "=", $request->input("business_type"));
        }

        if ($request->has("first_name") && !empty($request->input("first_name"))) {
            $customers->where("first_name", "LIKE", "%" . $request->input("first_name") . "%");
        }

        if ($request->has("middle_initial") && !empty($request->input("middle_initial"))) {
            $customers->where("middle_initial", "LIKE", "%" . $request->input("middle_initial") . "%");
        }

        if ($request->has("last_name") && !empty($request->input("last_name"))) {
            $customers->where("last_name", "LIKE", "%" . $request->input("last_name") . "%");
        }

        if ($request->has("suffix") && !empty($request->input("suffix"))) {
            $customers->where("fk_suffix", "=", $request->input("business_type"));
        }

        if ($request->has("date_birth") && !empty($request->input("date_birth"))) {
            $customers->where("date_birth", "LIKE", "%" . $request->input("date_birth") . "%");
        }

        if ($request->has("ssn") && !empty($request->input("ssn"))) {
            $customers->where("ssn", "LIKE", "%" . $request->input("ssn") . "%");
        }

        if ($request->has("gender") && !empty($request->input("gender"))) {
            $customers->where("fk_gender", "=", $request->input("business_type"));
        }

        if ($request->has("matiral_status") && !empty($request->input("matiral_status"))) {
            $customers->where("fk_matiral_status", "=", $request->input("business_type"));
        }

        if ($request->has("email") && !empty($request->input("email"))) {
            $customers->where("email", "LIKE", "%" . $request->input("email") . "%");
        }

        if ($request->has("address") && !empty($request->input("address"))) {
            $customers->where("address", "LIKE", "%" . $request->input("address") . "%");
        }

        if ($request->has("address_2") && !empty($request->input("address_2"))) {
            $customers->where("address_2", "LIKE", "%" . $request->input("address_2") . "%");
        }

        if ($request->has("county") && !empty($request->input("county"))) {
            $customers->where("fk_county", "=", $request->input("county"));
        }

        if ($request->has("city") && !empty($request->input("city"))) {
            $customers->where("city", "LIKE", "%" . $request->input("city") . "%");
        }

        if ($request->has("zip_code") && !empty($request->input("zip_code"))) {
            $customers->where("zip_code", "LIKE", "%" . $request->input("zip_code") . "%");
        }

        if ($request->has("phone") && !empty($request->input("phone"))) {
            $customers->where("phone", "LIKE", "%" . $request->input("phone") . "%");
        }

        if ($request->has("phone_2") && !empty($request->input("phone_2"))) {
            $customers->where("phone_2", "LIKE", "%" . $request->input("phone_2") . "%");
        }

        if ($request->has("registration_source") && !empty($request->input("registration_source"))) {
            $customers->where("fk_registration_source", "=", $request->input("registration_source"));
        }

        if ($request->has("status") && !empty($request->input("status"))) {
            $customers->where("fk_status", "=", $request->input("status"));
        }

        if ($request->has("phase") && !empty($request->input("phase"))) {
            $customers->where("fk_phase", "=", $request->input("phase"));
        }

        if ($request->has("legal_basis") && !empty($request->input("legal_basis"))) {
            $customers->where("fk_legal_basis", "=", $request->input("legal_basis"));
        }

        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $customers->where(function($query) use ($searchTxt) {
                $query->where("id","like","%{$searchTxt}%")
                      ->orWhere("first_name","like","%{$searchTxt}%")
                      ->orWhere("last_name","like","%{$searchTxt}%")
                      ->orWhere("address","like","%{$searchTxt}%")
                      ->orWhere("phone","like","%{$searchTxt}%")
                      ->orWhere("email","like","%{$searchTxt}%")
                      ->orWhereRaw("DATE_FORMAT(date_birth, '%m/%d/%Y') like '%{$searchTxt}%'")
                      ->orWhereRaw("TIMESTAMPDIFF(YEAR, date_birth, CURDATE()) like '%{$searchTxt}%'");
            });
        }

        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '1':
                    $customers->orderBy("id",$direction);
                    break;
                case '2':
                    $customers->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '3':
                    $customers->orderBy("date_birth",$direction);
                    break;
                case '5':
                    $customers->orderBy("address",$direction);
                    break;
                case '6':
                    $customers->orderBy("phone",$direction);
                    break;
                case '7':
                    $customers->orderBy("email",$direction);
                    break;
                case '8':
                    $customers->orderByRaw("TIMESTAMPDIFF(YEAR, date_birth, CURDATE()) $direction");
                    break;
            }
        }
        
        $totalRecords = $customers->count();
        $customers = $customers->skip($request->input('start'))
                            ->take($request->input('length'))
                            ->get();

        $filteredRecords = array();

        foreach($customers as $customer){            
            $filteredRecord = array();
            $filteredRecord["id"] = $customer->id;
            $filteredRecord["customer"]["text"] = $customer->first_name." ".$customer->last_name;
            $filteredRecord["customer"]["href"] = route('customers.update',['id' => $customer->id]);
            $filteredRecord["date_birth"] = ($customer->date_birth ? date('m/d/Y', strtotime($customer->date_birth)) : "");
            $filteredRecord["address"] = $customer->address;
            $filteredRecord["phone"] = $customer->phone;
            $filteredRecord["email"] = $customer->email;
            $filteredRecord["age"] = $customer->txt_age;
            array_push($filteredRecords, $filteredRecord);
        }
       

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);

        
    }

    public function search(Request $request)
    {

        $customers = CustomersModel::select("customers.*");
        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $customers->where(function ($query) use ($searchTxt) {
                $query->where("customers.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("customers.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("customers.email", "like", "%{$searchTxt}%")
                    ->orWhere("customers.phone", "like", "%{$searchTxt}%")
                    ->orWhere("customers.id", "like", "%{$searchTxt}%");
            });
        }




        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $customers->orderBy("id", $direction);
                    break;
                case '1':
                    $customers->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '2':
                    $customers->orderBy("email", $direction);
                    break;
                case '3':
                    $customers->orderBy("phone", $direction);
                    break;
            }
        }


        $totalRecords = $customers->count();
        $customers = $customers->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($customers as $customer) {

            $filteredRecord = array();

            $filteredRecord["id"] = $customer->id;
            $filteredRecord["name"] = "$customer->first_name $customer->last_name";
            $filteredRecord["email"] = $customer->email;
            $filteredRecord["phone"] = $customer->phone;
            $filteredRecord["actions"]["name"] = "$customer->first_name $customer->last_name";
            $filteredRecord["actions"]["id"] = $customer->id;
            array_push($filteredRecords, $filteredRecord);
        }


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);

    }

    public function showCreateForm()
    {

        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $matiral_statuses = MaritalStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        Utils::createLog(
            "The user has entered the form to create Customers",
            "customers.customers",
            "show"
        );


        return view('customers.create', [
            "business_types" => $business_types,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
        ]);
    }

    public function create(CreateCustomerRequest $request)
    {
        $entry_user = Auth::user();

        $customer = new CustomersModel();
        $customer->fk_business_type = $request->input('business_type');
        $customer->first_name = $request->input('first_name');
        $customer->middle_initial = $request->input('middle_initial');
        $customer->last_name = $request->input('last_name');
        $customer->fk_suffix = $request->input('suffix');
        $customer->date_birth = $request->input('date_birth');
        $customer->ssn = $request->input('ssn');
        $customer->fk_gender = $request->input('gender');
        $customer->fk_marital_status = $request->input('matiral_status');
        $customer->email = $request->input('email');
        $customer->address = $request->input('address');
        $customer->address_2 = $request->input('address_2');
        $customer->fk_county = $request->input('county');
        $customer->city = $request->input('city');
        $customer->zip_code = $request->input('zip_code');
        $customer->phone = $request->input('phone');
        $customer->phone_2 = $request->input('phone_2');
        $customer->fk_registration_s = $request->input('registration_source');
        $customer->fk_customer = $request->input('referring_customer_id');
        $customer->fk_status = $request->input("status");
        $customer->fk_phase = $request->input("phase");
        $customer->fk_legal_basis = $request->input("legal_basis");
        $customer->fk_agent = $request->input('contact_agent_id'); 
        $customer->fk_entry_user = $entry_user->id;
        $customer->save();

        Utils::createLog(
            "The user has created a new Customer with ID: ".$customer->id,
            "customers.customers",
            "create"
        );       

        return redirect(route('customers.show'))->with('message', 'Customer created successfully');
    }

    public function showUpdateForm($id){

        $customer = CustomersModel::find($id);
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $matiral_statuses = MaritalStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $customer_statuses = CustomerStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $phases = PhasesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $legal_basis_m = LegalBasisModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        $cuids = CuidsModel::where("fk_customer","=",$id)->get();

        $selectedCuid = null;
        $errors = session('errors', new ViewErrorBag);

        if ($errors->editCUIDForm->any()) {
            if ($errors->hasBag('editCUIDForm')) {
                $selectedCuidID = session()->getOldInput('cuidID');
                $selectedCuid = CuidsModel::find($selectedCuidID);
            }    
        }


        $policies = []; //Datatable

     
        Utils::createLog(
            "The user has entered the form to update Customers with ID:".$customer->id,
            'customers.customers',
            "show"
        );

        return view('customers.update', [
            'customer' => $customer,
            "business_types" => $business_types,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources,
            "customer_statuses" => $customer_statuses,
            "phases" => $phases,
            "legal_basis_m" => $legal_basis_m,
            "policies" => $policies,
            "carriers" => $carriers,
            "business_segments" => $business_segments,
            'cuids' => $cuids,
            'selectedCuid' => $selectedCuid
        ]);
    }

    public function update($id, CreateCustomerRequest $request)
    {

        $customer = CustomersModel::find($id);
        $customer->fk_business_type = $request->input('business_type');
        $customer->first_name = $request->input('first_name');
        $customer->middle_initial = $request->input('middle_initial');
        $customer->last_name = $request->input('last_name');
        $customer->fk_suffix = $request->input('suffix');
        $customer->date_birth = $request->input('date_birth');
        $customer->ssn = $request->input('ssn');
        $customer->fk_gender = $request->input('gender');
        $customer->fk_marital_status = $request->input('matiral_status');
        $customer->email = $request->input('email');
        $customer->address = $request->input('address');
        $customer->address_2 = $request->input('address_2');
        $customer->fk_county = $request->input('county');
        $customer->city = $request->input('city');
        $customer->zip_code = $request->input('zip_code');
        $customer->phone = $request->input('phone');
        $customer->phone_2 = $request->input('phone_2');
        $customer->fk_registration_s = $request->input('registration_source');
        $customer->fk_customer = $request->input('referring_customer_id');
        $customer->fk_status = $request->input("status");
        $customer->fk_phase = $request->input("phase");
        $customer->fk_legal_basis = $request->input("legal_basis");
        $customer->fk_agent = $request->input('contact_agent_id');
        $customer->save();

        Utils::createLog(
            "The user has modified the Customer with ID:".$customer->id,
            "customers.customers",
            "update"
        );

        return redirect(route('customers.show'))->with('message', 'Customer updated successfully');
    }

    public function searchSubscribers(Request $request)
    {
        $customers = CustomersModel::select("customers.*")->whereHas("status", function($query){
            $query->whereRaw("LOWER(name) = 'customer'");
        });

        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $customers->where(function ($query) use ($searchTxt) {
                $query->where("customers.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("customers.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("customers.email", "like", "%{$searchTxt}%")
                    ->orWhere("customers.phone", "like", "%{$searchTxt}%")
                    ->orWhere("customers.id", "like", "%{$searchTxt}%");
            });
        }




        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $customers->orderBy("id", $direction);
                    break;
                case '1':
                    $customers->orderByRaw("CONCAT(first_name,' ',last_name) $direction");
                    break;
                case '2':
                    $customers->orderBy("email", $direction);
                    break;
                case '3':
                    $customers->orderBy("phone", $direction);
                    break;
            }
        }


        $totalRecords = $customers->count();
        $customers = $customers->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($customers as $customer) {

            $filteredRecord = array();

            $filteredRecord["id"] = $customer->id;
            $filteredRecord["name"] = "$customer->first_name $customer->last_name";
            $filteredRecord["email"] = $customer->email;
            $filteredRecord["phone"] = $customer->phone;
            $filteredRecord["actions"]["name"] = "$customer->first_name $customer->last_name";
            $filteredRecord["actions"]["id"] = $customer->id;
            array_push($filteredRecords, $filteredRecord);
        }


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);

    }

}
