<?php

namespace App\Http\Controllers;

use App\Models\BusinessTypesModel;
use App\Models\CountiesModel;
use App\Models\CustomersModel;
use App\Models\GendersModel;
use App\Models\MaritalStatusModel;
use App\Models\RegistrationSourcesModel;
use App\Models\SuffixesModel;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function show(Request $request){
        $customers = CustomersModel::select("customers.*");
        
        if($request->has("business_type") && !empty($request->input("business_type"))){
            $customers = $customers->where("fk_business_type","=", $request->input("business_type"));
        }

        if($request->has("first_name") && !empty($request->input("first_name"))){
            $customers = $customers->where("first_name","LIKE","%".$request->input("first_name")."%");
        }

        if($request->has("middle_initial") && !empty($request->input("middle_initial"))){
            $customers = $customers->where("middle_initial","LIKE","%".$request->input("middle_initial")."%");
        }

        if($request->has("last_name") && !empty($request->input("last_name"))){
            $customers = $customers->where("last_name","LIKE","%".$request->input("last_name")."%");
        }

        if($request->has("suffix") && !empty($request->input("suffix"))){
            $customers = $customers->where("fk_suffix","=", $request->input("business_type"));
        }

        if($request->has("date_birth") && !empty($request->input("date_birth"))){
            $customers = $customers->where("date_birth","LIKE","%".$request->input("date_birth")."%");
        }

        if($request->has("ssn") && !empty($request->input("ssn"))){
            $customers = $customers->where("ssn","LIKE","%".$request->input("ssn")."%");
        }

        if($request->has("gender") && !empty($request->input("gender"))){
            $customers = $customers->where("fk_gender","=", $request->input("business_type"));
        }

        if($request->has("matiral_status") && !empty($request->input("matiral_status"))){
            $customers = $customers->where("fk_matiral_status","=", $request->input("business_type"));
        }

        if($request->has("email") && !empty($request->input("email"))){
            $customers = $customers->where("email","LIKE","%".$request->input("email")."%");
        }

        if($request->has("address") && !empty($request->input("address"))){
            $customers = $customers->where("address","LIKE","%".$request->input("address")."%");
        }

        if($request->has("address_2") && !empty($request->input("address_2"))){
            $customers = $customers->where("address_2","LIKE","%".$request->input("address_2")."%");
        }

        if($request->has("county") && !empty($request->input("county"))){
            $customers = $customers->where("fk_county","=", $request->input("county"));
        }

        if($request->has("city") && !empty($request->input("city"))){
            $customers = $customers->where("city","LIKE","%".$request->input("city")."%");
        }

        if($request->has("zip_code") && !empty($request->input("zip_code"))){
            $customers = $customers->where("zip_code","LIKE","%".$request->input("zip_code")."%");
        }

        if($request->has("phone") && !empty($request->input("phone"))){
            $customers = $customers->where("phone","LIKE","%".$request->input("phone")."%");
        }

        if($request->has("phone_2") && !empty($request->input("phone_2"))){
            $customers = $customers->where("phone_2","LIKE","%".$request->input("phone_2")."%");
        }

        if($request->has("registration_source") && !empty($request->input("registration_source"))){
            $customers = $customers->where("fk_registration_source","=", $request->input("registration_source"));
        }
        
        $customers = $customers->get();

        $business_types = BusinessTypesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $matiral_statuses = MaritalStatusModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();

        return view('customers.show',[
            "customers" => $customers,
            "business_types" => $business_types,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources
        ]);
    }

    public function search(Request $request){

        $customers = CustomersModel::select("customers.*");
       
        if($request->has("email") && !empty($request->input("email"))){
            $customers = $customers->where("email","LIKE","%".$request->input("email")."%");
        }

        if($request->has("phone") && !empty($request->input("phone"))){
            $customers = $customers->where("phone","LIKE","%".$request->input("phone")."%");
        }
        
        $customers = $customers->get();

        return view('customers.partials.search',[
            "customers" => $customers
        ]);
    }
    


    public function showCreateForm(){


        $business_types = BusinessTypesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $matiral_statuses = MaritalStatusModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $suffixes = SuffixesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $genders = GendersModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();
        $registration_sources = RegistrationSourcesModel::where("status","=","1")->orderBy("sort_order", "ASC")->get();

        return view('customers.create',[
            "business_types" => $business_types,
            "matiral_statuses" => $matiral_statuses,
            "suffixes" => $suffixes,
            "genders" => $genders,
            "counties" => $counties,
            "registration_sources" => $registration_sources
        ]);
    }
}
