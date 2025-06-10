<?php

namespace App\Http\Controllers\Policies;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Policies\CreatePolicyRequest;
use App\Http\Requests\Policies\UpdatePolicyRequest;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\EnrollmentMethodsModel;
use App\Models\MultiTable\PolicyStatusModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\RelationshipsModel;
use App\Models\Policies\CountiesModel;
use App\Models\Policies\DependentsModel;
use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliciesController extends Controller
{
    public function show(Request $request)
    {

        session()->flashInput($request->all());

        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        Utils::createLog(
            "The user entered the policies list.",
            "policies.policies",
            "show"
        );
        return view('policies.show', [
            "product_types" => $product_types
        ]);
    }

    public function datatableAjax(Request $request)
    {

        $policies = PoliciesModel::select(
            "policies.*"
        )
            ->leftJoin('customers', 'customers.id', '=', 'policies.fk_customer')
            ->leftJoin('agent_numbers', 'agent_numbers.id', '=', 'policies.fk_agent_number')
            ->leftJoin('agents', 'agents.id', '=', 'agent_numbers.fk_agent')
            ->leftJoin('products', 'products.id', '=', 'policies.fk_product')
            ->leftJoin('policy_status', 'policy_status.id', '=', 'policies.fk_policy_status')
            ->leftJoin('carriers', 'carriers.id', '=', 'products.fk_carrier')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.fk_product_type');

        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $policies->where(function ($query) use ($searchTxt) {
                $query->where("policies.id", "like", "%{$searchTxt}%")
                    ->orWhere("customers.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("customers.last_name", "like", "%{$searchTxt}%")
                    ->orWhereRaw("DATE_FORMAT(customers.date_birth, '%m/%d/%Y') like '%{$searchTxt}%'")
                    ->orWhere("carriers.name", "like", "%{$searchTxt}%")
                    ->orWhere("product_types.name", "like", "%{$searchTxt}%")
                    ->orWhere("products.description", "like", "%{$searchTxt}%")
                    ->orWhere("policies.application_id", "like", "%{$searchTxt}%")
                    ->orWhere("policies.contract_id", "like", "%{$searchTxt}%")
                    ->orWhereRaw("DATE_FORMAT(policies.original_effective_date, '%m/%d/%Y') like '%{$searchTxt}%'")
                    ->orWhereRaw("DATE_FORMAT(policies.benefit_effective_date, '%m/%d/%Y') like '%{$searchTxt}%'")
                    ->orWhereRaw("DATE_FORMAT(policies.cancel_date, '%m/%d/%Y') like '%{$searchTxt}%'")
                    ->orWhere("policy_status.name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.first_name", "like", "%{$searchTxt}%")
                    ->orWhere("agents.last_name", "like", "%{$searchTxt}%")
                    ->orWhere("agent_numbers.number", "like", "%{$searchTxt}%");
            });
        }

        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $policies->orderBy("policies.id", $direction);
                    break;
                case '1':
                    $policies->orderByRaw("CONCAT(customers.first_name,' ',customers.last_name) $direction");
                    break;
                case '2':
                    $policies->orderBy("customers.date_birth", $direction);
                    break;
                case '3':
                    $policies->orderBy("carriers.name", $direction);
                    break;
                case '4':
                    $policies->orderBy("product_types.name", $direction);
                    break;
                case '5':
                    $policies->orderBy("products.description", $direction);
                    break;
                case '6':
                    $policies->orderBy("policies.application_id", $direction);
                    break;
                case '7':
                    $policies->orderBy("policies.contract_id", $direction);
                    break;
                case '8':
                    $policies->orderBy("policies.original_effective_date", $direction);
                    break;
                case '9':
                    $policies->orderBy("policies.benefit_effective_date", $direction);
                    break;
                case '10':
                    $policies->orderBy("policies.cancel_date", $direction);
                    break;
                case '11':
                    $policies->orderBy("policy_status.name", $direction);
                    break;
                case '12':
                    $policies->orderByRaw("CONCAT(agents.first_name,' ',agents.last_name) $direction");
                    break;
                case '13':
                    $policies->orderBy("agent_numbers.number", $direction);
                    break;
            }
        }




        $totalRecords = $policies->count();
        $policies = $policies->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($policies as $policy) {

            $filteredRecord = array();
            $filteredRecord["policy_id"]["href"] = route('policies.update', ['id' => $policy->id]);
            $filteredRecord["policy_id"]["text"] = $policy->id;

            $filteredRecord["suscriber"]["href"] = route('customers.update', ['id' => $policy->fk_customer]);
            $filteredRecord["suscriber"]["text"] = $policy->customer->first_name . " " . $policy->customer->last_name;
            $filteredRecord["date_birth"] = ($policy->customer->date_birth ? date('m/d/Y', strtotime($policy->customer->date_birth)) : "");
            $filteredRecord["carrier"] = $policy->product?->carrier?->name;
            $filteredRecord["product_type"] = $policy->product?->product_type?->name ?? "";

            $filteredRecord["product"]["href"] = route('products.update', ['id' => $policy->fk_product]);
            $filteredRecord["product"]["text"] = $policy->product->description;

            $filteredRecord["application_id"] = $policy->application_id;
            $filteredRecord["contract_id"] = $policy->contract_id;

            $filteredRecord["original_effective_date"] = ($policy->original_effective_date ? date('m/d/Y', strtotime($policy->original_effective_date)) : "");
            $filteredRecord["benefit_effective_date"] = ($policy->benefit_effective_date ? date('m/d/Y', strtotime($policy->benefit_effective_date)) : "");
            $filteredRecord["cancel_date"] = ($policy->cancel_date ? date('m/d/Y', strtotime($policy->cancel_date)) : "");

            $filteredRecord["status"] = $policy->policy_status->name;

            $filteredRecord["agent"]["href"] = route('agents.update', ['id' => $policy->agent_number->fk_agent]);
            $filteredRecord["agent"]["text"] = $policy->agent_number->agent->first_name . " " . $policy->agent_number->agent->last_name;

            $filteredRecord["agent_number"]["href"] = route('agent_numbers.update', ['id' => $policy->agent_number->id]);
            $filteredRecord["agent_number"]["text"] = $policy->agent_number->number;
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
        $agentNumbers = AgentNumbersModel::with(["agent_status" => function ($query) {
            $query->whereRaw("LOWER(name) = 'Active'");
        }])->get();

        $products = ProductsModel::all();

        $enrollment_methods = EnrollmentMethodsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $policy_statuses = PolicyStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $relationships = RelationshipsModel::select("id", "name")->where("status", "=", "1")->orderBy("sort_order", "ASC")->get()->makeHidden(['txt_status']);

        Utils::createLog(
            "The user has entered the form to create policies.",
            "policies.policies",
            "show"
        );
        return view('policies.create', [
            "agentNumbers" => $agentNumbers,
            "products" => $products,
            "enrollment_methods" => $enrollment_methods,
            "counties" => $counties,
            "policy_statuses" => $policy_statuses,
            "relationships" => $relationships
        ]);
    }

    public function create(CreatePolicyRequest $request)
    {
        $entry_user = Auth::user();
        $agent_number = AgentNumbersModel::find($request->input("agent_number"));
        $customerID = $request->input("subscriber_id");

        if (!$request->has("subscriber_id") || empty($request->input("subscriber_id"))) {
            $customer = new CustomersModel();
            $customer->fk_business_type = 1;
            $customer->first_name = $request->input('first_name');
            $customer->last_name = $request->input('last_name');
            $customer->date_birth = $request->input('date_birth');
            $customer->ssn = $request->input('ssn');
            $customerStatus = CustomerStatusModel::whereRaw("LOWER(name) = 'customer'")->first();
            $customer->fk_status = $customerStatus->id;
            $customer->fk_agent = $agent_number->agent->id;
            $customer->fk_entry_user = $entry_user->id;
            $customer->save();
            $customerID = $customer->id;

            Utils::createLog(
                "The user has created a new customer with ID: " . $customer->id." from the form to create policies",
                "policies.policies.customers",
                "create"
            );
        }

        $policy = new PoliciesModel();
        $policy->app_submit_date = $request->input("app_submit_date");
        $policy->request_effective_date = $request->input("request_effective_date");
        $policy->original_effective_date = $request->input("original_effective_date");
        $policy->application_id = $request->input("application_id");
        $policy->eligibility_id = $request->input("eligibility_id");
        $policy->proposal_id = $request->input("proposal_id");
        $policy->contract_id = $request->input("contract_id");
        $policy->num_adults = $request->input("num_adults");
        $policy->num_dependents = $request->input("num_dependents");
        $policy->premium = $request->input("premium");
        $policy->cancel_date = $request->input("cancel_date");
        $policy->benefit_effective_date = $request->input("benefit_effective_date");
        $policy->reenrollment = $request->input("reenrollment");
        $policy->entry_method = 0;
        $policy->review_note = $request->input("review_note");
        $policy->non_commissionable = $request->has("non_commissionable");
        $policy->fk_policy_status = $request->input("policy_status");
        $policy->fk_customer = $customerID;
        $policy->fk_agent_number = $request->input("agent_number");
        $policy->fk_product = $request->input("product");
        $policy->fk_enrollment_method = $request->input("enrollment_method");
        $policy->fk_county = $request->input("county");
        $policy->fk_entry_user = $entry_user->id;
        $policy->save();
        
        if ($request->has("dependent_first_name")) {
            foreach ($request->input("dependent_first_name") as $index => $first_name) {
                $dependent = new DependentsModel();
                $dependent->first_name = $request->input("dependent_first_name")[$index];
                $dependent->last_name = $request->input("dependent_last_name")[$index];
                $dependent->date_birth = $request->input("dependent_date_birth")[$index];
                $dependent->fk_relationship = $request->input("dependent_relationship")[$index];
                $dependent->date_added = $request->input("dependent_date_add")[$index];
                $dependent->fk_policy = $policy->id;
                $dependent->save();
            }
        }

        Utils::createLog(
            "The user has created a new policy with ID: " . $policy->id,
            "policies.policies",
            "create"
        );
        
        return redirect(route('policies.show'))->with('message', 'Policy created successfully');
    }

    public function showUpdateForm($id)
    {
        $policy = PoliciesModel::find($id);
        $agentNumbers = AgentNumbersModel::with(["agent_status" => function ($query) {
            $query->whereRaw("LOWER(name) = 'Active'");
        }])->get();

        $products = ProductsModel::all();

        $enrollment_methods = EnrollmentMethodsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $counties = CountiesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $policy_statuses = PolicyStatusModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $relationships = RelationshipsModel::select("id", "name")->where("status", "=", "1")->orderBy("sort_order", "ASC")->get()->makeHidden(['txt_status']);
        Utils::createLog(
            "The user has entered the form to update policies with ID: ".$policy->id,
            "policies.policies",
            "show"
        );
        return view('policies.update', [
            "policy" => $policy,
            "agentNumbers" => $agentNumbers,
            "products" => $products,
            "enrollment_methods" => $enrollment_methods,
            "counties" => $counties,
            "policy_statuses" => $policy_statuses,
            "relationships" => $relationships
        ]);
    }

    public function update($id, UpdatePolicyRequest $request)
    {
        $policy = PoliciesModel::find($id);
        $policy->app_submit_date = $request->input("app_submit_date");
        $policy->request_effective_date = $request->input("request_effective_date");
        $policy->original_effective_date = $request->input("original_effective_date");
        $policy->application_id = $request->input("application_id");
        $policy->eligibility_id = $request->input("eligibility_id");
        $policy->proposal_id = $request->input("proposal_id");
        $policy->contract_id = $request->input("contract_id");
        $policy->num_adults = $request->input("num_adults");
        $policy->num_dependents = $request->input("num_dependents");
        $policy->premium = $request->input("premium");
        $policy->cancel_date = $request->input("cancel_date");
        $policy->benefit_effective_date = $request->input("benefit_effective_date");
        $policy->reenrollment = $request->input("reenrollment");
        $policy->entry_method = 0;
        $policy->review_note = $request->input("review_note");
        $policy->non_commissionable = $request->has("non_commissionable");
        $policy->fk_policy_status = $request->input("policy_status");
        $policy->fk_product = $request->input("product");
        $policy->fk_enrollment_method = $request->input("enrollment_method");
        $policy->fk_county = $request->input("county");
        $policy->fk_agent_number_1 = $request->input("agent_number_1");
        $policy->fk_agent_number_2 = $request->input("agent_number_2");
        $policy->save();


        $arrDependentIds = [];
        if ($request->has("dependent_ids")) {
            $arrDependentIds = $request->input("dependent_ids");
        }
        foreach ($policy->dependents as $dependent) {
            if (!in_array($dependent->id, $arrDependentIds)) {
                $dependent->delete();
            }
        }


        if ($request->has("dependent_first_name")) {
            foreach ($request->input("dependent_first_name") as $index => $first_name) {
                $dependentID = $arrDependentIds[$index] ?? null;
                if ($dependentID == null) {
                    $dependent = new DependentsModel();
                } else {
                    $dependent = DependentsModel::find($dependentID);
                }
                $dependent->first_name = $request->input("dependent_first_name")[$index];
                $dependent->last_name = $request->input("dependent_last_name")[$index];
                $dependent->date_birth = $request->input("dependent_date_birth")[$index];
                $dependent->fk_relationship = $request->input("dependent_relationship")[$index];
                $dependent->date_added = $request->input("dependent_date_add")[$index];
                $dependent->fk_policy = $policy->id;
                $dependent->save();
            }
        }

        Utils::createLog(
            "The user has modified the policy with ID: ".$policy->id,
            "policies.policies",
            "update"
        );

        return redirect(route('policies.show'))->with('message', 'Policy updated successfully');
    }
}
