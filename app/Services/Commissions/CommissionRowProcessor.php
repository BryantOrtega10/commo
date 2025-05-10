<?php

namespace App\Services\Commissions;

use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Customers\CuidsModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class CommissionRowProcessor
{
    private $commissionRow;
    private $commissionData;

    private $notes = [];

    private $subscriber = null;
    private $product = null;
    private $agentNumber = null;
    private $policy = null;

    public function process(int $commissionRowId): void
    {
        $this->commissionRow = CommissionUploadRowsModel::with('commission_upload')->find($commissionRowId);
        $this->commissionRow->status = 1;
        $this->commissionRow->save();
        $this->commissionData = json_decode($this->commissionRow->data);

        $this->commissionRow->commission_upload->increment('processed_rows');
        //N start
        $commissionWithoutPolicy = ["Prior Balance", "Adjustment"];
        $policyIsNeed = true;
        if ($this->isValidIndex('comp_type') && in_array($this->getProp("comp_type"), $commissionWithoutPolicy)) {
            $policyIsNeed = false;
        }

        $this->findAgentNumber();
        if (isNull($this->agentNumber)) {
            array_push($this->notes, "Agent number not found");
            return;
        }
        if ($policyIsNeed) {
            $this->findSubscriber();
            $this->findProduct();
            $countPolicies = $this->findPolicy();
            if ($countPolicies > 1) {
                array_push($this->notes, "More than one policy was found");
                return;
            }
            if ($countPolicies == 0) {
                array_push($this->notes, "Policy not found, trying to create it");
                if (isNull($this->subscriber)) {
                    array_push($this->notes, "Subscriber not found, trying to create it");
                    if (!$this->createSubscriber()) {
                        array_push($this->notes, "The subscriber could not be created");
                        return;
                    }
                }
                if (isNull($this->product)) {
                    array_push($this->notes, "Product not found, trying to create it");
                    if (!$this->createProduct()) {
                        array_push($this->notes, "The product could not be created");
                        return;
                    }
                }
                if (!$this->createPolicy()) {
                    array_push($this->notes, "The policy could not be created");
                    return;
                }
            }
        }
    }

    public function findSubscriber()
    {
        if ($this->isValidIndex('fb_uid')) {
            $queryVal = $this->getProp('fb_uid');
            $this->subscriber = CustomersModel::whereHas("cuids", function ($query) use ($queryVal) {
                $query->whereRaw("LOWER(name) = LOWER('" . $queryVal . "')");
            })
                ->first(); //Pregunta: El CUID es unico por customer?
        }
        if (isNull($this->subscriber) && $this->isValidIndex('subscriber_last') && $this->isValidIndex('subscriber_first')) {
            $subscribers = CustomersModel::whereRaw("LOWER(first_name) = LOWER(" . $this->getProp('subscriber_first') . ")")
                ->whereRaw("LOWER(first_name) = LOWER(" . $this->getProp('subscriber_last') . ")")
                ->get();
            if (sizeof($subscribers) == 1) {
                $this->subscriber = $subscribers[0];
            }
        }
        if (isNull($this->subscriber) && $this->isValidIndex('group_name')) {
            $subscribers = CustomersModel::whereRaw("LOWER(first_name) = LOWER(" . $this->getProp('group_name') . ")")->get();
            if (sizeof($subscribers) == 1) {
                $this->subscriber = $subscribers[0];
            }
        }
    }

    public function findProduct()
    {
        //Pregunta: Que pasa si encuentro mas de un producto con la busqueda?
        $commissionUpload = $this->commissionRow->commission_upload;
        if ($this->isValidIndex('coverage') && $commissionUpload->fk_template == 1) { //Group
            $queryVal = $this->getProp('coverage');
            $products = ProductsModel::where(function ($query) use($queryVal){
                $query->whereHas("product_type", function ($query2) use ($queryVal) {
                    $query2->whereRaw("LOWER(name) = LOWER('" . $queryVal . "')");
                })
                ->orWhereHas("product_alias", function ($query2) use ($queryVal) {
                    $query2->whereRaw("LOWER(alias) = LOWER('" . $queryVal . "')");
                })
                ->orWhere("description", "like", $queryVal."%");
            })->where(function ($query) {
                $query->where("fk_business_type", "=", "2") //Group
                    ->orWhereNull("fk_business_type");
            })
            ->where("fk_carrier", "=", $commissionUpload->fk_carrier)
            ->get();

            
            if (sizeof($products) == 1) {
                $this->product = $products[0];
            }

            
        }
        if ($this->isValidIndex('product_type')) {
            $queryVal = $this->getProp('product_type');
            $products = ProductsModel::where(function ($query) use ($queryVal) {
                $query->whereHas("product_type", function ($query2) use ($queryVal) {
                    $query2->whereRaw("LOWER(name) = LOWER('" . $queryVal . "')");
                })
                ->orWhereHas("product_alias", function ($query2) use ($queryVal) {
                    $query2->whereRaw("LOWER(alias) = LOWER('" . $queryVal . "')");
                })
                ->orWhere("description", "like", $queryVal."%");
            })
            ->where(function ($query) {
                $query->where("fk_business_type", "=", "1") //Individual
                    ->orWhereNull("fk_business_type");
            })
            ->where("fk_carrier", "=", $commissionUpload->fk_carrier);

            if ($this->isValidIndex('product_tier_metal_level')) {
                $tier = $this->getProp('product_tier_metal_level');
                $products->whereHas("tier", function ($query) use ($tier) {
                    $query->whereRaw("LOWER(name) = LOWER('" . $tier . "')");
                });
            }
            if ($this->isValidIndex('product_tiermetal')) {
                $tier = $this->getProp('product_tiermetal');
                $products->whereHas("tier", function ($query) use ($tier) {
                    $query->whereRaw("LOWER(name) = LOWER('" . $tier . "')");
                });
            }
            $products = $products->get();

            if (sizeof($products) == 1) {
                $this->product = $products[0];
            }
        }
    }
    
    public function findAgentNumber()
    {

        if ($this->isValidIndex('agent_code')) {
            $this->agentNumber = AgentNumbersModel::where("number", "=", $this->getProp('agent_code'))->first();
        }
        if (isNull($this->agentNumber) && $this->isValidIndex('agency_code') && $this->isValidIndex('wrt_agt')) {
            $number = $this->getProp('agency_code') . $this->getProp('wrt_agt');
            $this->agentNumber = AgentNumbersModel::where("number", "=", $number)->first();
        }
        if (isNull($this->agentNumber) && $this->isValidIndex('agy') && $this->isValidIndex('wrt_agt')) {
            $number = $this->getProp('agy') . $this->getProp('wrt_agt');
            $this->agentNumber = AgentNumbersModel::where("number", "=", $number)->first();
        }
        if (isNull($this->agentNumber) && $this->isValidIndex('agent_id')) {
            $this->agentNumber = AgentNumbersModel::where("number", "=", $this->getProp('agent_id'))->first();
        }
        if (isNull($this->agentNumber) && $this->isValidIndex('agent_last') && $this->isValidIndex('agent_first')) {

            $agent_last = $this->getProp('agent_last');
            $agent_first = $this->getProp('agent_first');
            $agentNumbers = AgentNumbersModel::whereHas("agent", function ($query) use ($agent_first, $agent_last) {
                $query->whereRaw("LOWER(first_name) = LOWER('" . $agent_first . "')")
                    ->whereRaw("LOWER(last_name) = LOWER('" . $agent_last . "')");
            })->get();
            if (sizeof($agentNumbers) == 1) {
                $this->agentNumber = $agentNumbers[0];
            }
        }
        if (isNull($this->agentNumber) && $this->isValidIndex('agent_last_name') && $this->isValidIndex('agent_first_name')) {

            $agent_first = $this->getProp('agent_first_name');
            $agent_last = $this->getProp('agent_last_name');
            $agentNumbers = AgentNumbersModel::whereHas("agent", function ($query) use ($agent_first, $agent_last) {
                $query->whereRaw("LOWER(first_name) = LOWER('" . $agent_first . "')")
                    ->whereRaw("LOWER(last_name) = LOWER('" . $agent_last . "')");
            })->get();
            if (sizeof($agentNumbers) == 1) {
                $this->agentNumber = $agentNumbers[0];
            }
        }
    }

    private function findPolicy()
    {
        $policies = PoliciesModel::select("policies.*");
        if ($this->isValidIndex('group_no')) {
            $policies->where("contract_id", "=", $this->getProp('group_no'));
        }
        if ($this->isValidIndex('member_contract_id')) {
            $policies->where("contract_id", "=", $this->getProp('member_contract_id'));
        }
        if ($this->isValidIndex('member_contract_no')) {
            $policies->where("contract_id", "=", $this->getProp('member_contract_no'));
        }
        //Pregunta: Que pasa si se encuentra una poliza con el # contrato pero se envia el agente/producto/subscriber distinto?
        if (!isNull($this->subscriber)) {
            $policies->where("fk_customer", "=", $this->subscriber->id);
        }
        if (!isNull($this->product)) {
            $policies->where("fk_product", "=", $this->product->id);
        }
        if (!isNull($this->agentNumber)) {
            $policies->where("fk_agent_number", "=", $this->agentNumber->id);
        }
        $count = $policies->count();

        if ($count == 1) {
            $this->policy = $policies->first();
        }

        return $count;
    }

    private function createSubscriber()
    {
        $commissionUpload = $this->commissionRow->commission_upload;

        $entry_user = Auth::user();
        $customer = new CustomersModel();


        if ($commissionUpload->fk_template == 1) { //Group
            if (!$this->isValidIndex('group_name')) {
                return false;
            }
            $customer->fk_business_type = 2;
            $customer->first_name = $this->getProp('group_name');
        } else {
            if (!$this->isValidIndex('subscriber_first') || !$this->isValidIndex('subscriber_last')) {
                return false;
            }
            $customer->fk_business_type = 1;
            $customer->first_name = $this->getProp('subscriber_first');
            if ($this->isValidIndex('subscriber_middle')){
                $customer->middle_initial = $this->getProp('subscriber_middle');
            }
            $customer->last_name = $this->getProp('subscriber_last');
            if ($this->isValidIndex('subscriber_middle')){
                $customer->middle_initial = $this->getProp('subscriber_middle');
            }
        }       
        $customerStatus = CustomerStatusModel::whereRaw("LOWER(name) = 'customer'")->first();
        $customer->fk_status = $customerStatus->id;
        $customer->fk_agent = $this->agentNumber->id; 
        $customer->fk_entry_user = $entry_user->id;
        $customer->save();


        if ($this->isValidIndex('fb_uid')){
            $carrier = $commissionUpload->fk_carrier;
            $cuid = new CuidsModel();
            $cuid->name = $this->getProp('fb_uid');
            $cuid->fk_carrier = $carrier;
            $cuid->fk_customer = $customer->id;
            $cuid->save();
        }
        $this->subscriber = $customer;
        return true;
    }

    private function createProduct(){
        $commissionUpload = $this->commissionRow->commission_upload;
        $entry_user = Auth::user();        
        $product = new ProductsModel();
        
        $product->fk_carrier = $commissionUpload->fk_carrier;
        if ($commissionUpload->fk_template == 1) { //Group
            $product->fk_business_type = 2;
            if (!$this->isValidIndex('coverage')){
                return false;
            }
            $product->description = $this->getProp('coverage')." AutoEntry";
            $productType = ProductTypesModel::whereRaw("LOWER(name) = LOWER('".$this->getProp('coverage')."')")->first();
            if(!isset($productType)){
                $productType = new ProductTypesModel();
                $productType->name = $this->getProp('coverage');
                $productType->save();
            }                
            $product->fk_product_type = $productType->id;
        }
        else{
            if (!$this->isValidIndex('product_type')){
                return false;
            }
            $product->description = $this->getProp('product_type')." AutoEntry";
            $productType = ProductTypesModel::whereRaw("LOWER(name) = LOWER('".$this->getProp('product_type')."')")->first();
            if(!isset($productType)){
                $productType = new ProductTypesModel();
                $productType->name = $this->getProp('product_type');
                $productType->save();
            }
            
            $product->fk_product_type = $productType->id;
            if ($this->isValidIndex('product_tier_metal_level')){
                $tier = TiersModel::whereRaw("LOWER(name) = LOWER('".$this->getProp('product_tier_metal_level')."')")->first();
                if(!isset($tier)){
                    $tier = new ProductTypesModel();
                    $tier->name = $this->getProp('product_tier_metal_level');
                    $tier->save();
                }                
                $product->fk_tier = $tier->id;
            }
            if ($this->isValidIndex('product_tiermetal')){
                $tier = TiersModel::whereRaw("LOWER(name) = LOWER('".$this->getProp('product_tiermetal')."')")->first();
                if(!isset($tier)){
                    $tier = new ProductTypesModel();
                    $tier->name = $this->getProp('product_tiermetal');
                    $tier->save();
                }                
                $product->fk_tier = $tier->id;
            }
            $product->fk_business_type = 1;
        }
        
        $product->fk_entry_user = $entry_user->id;
        $product->save();
        $this->product = $product;
        return true;
    }

    private function createPolicy(){
        $commissionUpload = $this->commissionRow->commission_upload;
        $entry_user = Auth::user();
        $policy = new PoliciesModel();

        $policy->fk_customer = $this->subscriber->id;
        $policy->fk_agent_number = $this->agentNumber->id;
        $policy->fk_product = $this->product->id;
        if ($this->isValidIndex('county_name')) {
            $county = CountiesModel::where("name","like", '%'.$this->getProp('county_name').'%')->first();
            if(!isset($county)){
                $county = new CountiesModel();
                $county->name = $this->getProp('county_name');
                $county->save();
            }
            $policy->fk_county = $county->id; 
        }
        if ($commissionUpload->fk_template == 1) { //Group
            if (!$this->isValidIndex('group_no')) {
                return false;
            }
            $policy->contract_id = $this->getProp('group_no');
        }
        else{
            if (!$this->isValidIndex('member_contract_id') && !$this->isValidIndex('member_contract_no')) {
                return false;
            }
            if ($this->isValidIndex('member_contract_id')){
                $policy->contract_id = $this->getProp('member_contract_id');
            }
            if ($this->isValidIndex('member_contract_no')){
                $policy->contract_id = $this->getProp('member_contract_no');
            }
        }        

        if ($this->isValidIndex('member_count')) {
            $policy->num_dependents = $this->getProp('member_count');
        }
        if ($this->isValidIndex('contract_count')) {
            $policy->num_dependents = $this->getProp('contract_count');
        }

        if ($this->isValidIndex('effective_date')) {
            $policy->request_effective_date = $this->getProp('effective_date');
        }

        if ($this->isValidIndex('original_effective_date')) {
            $policy->original_effective_date = $this->getProp('original_effective_date');
        }

        if ($this->isValidIndex('benefit_effective_date')) {
            $policy->original_effective_date = $this->getProp('benefit_effective_date');
        }

        if ($this->isValidIndex('cancel_date')) {
            $policy->cancel_date = $this->getProp('cancel_date');
            $policy->fk_policy_status = 2;
        }
        else{
            $policy->fk_policy_status = 1;
        }
        $policy->entry_method = 1;
        $policy->fk_entry_user = $entry_user->id;
                
        $policy->validation_date = date("Y-m-d");
        $policy->validation_filename = $commissionUpload->name;
        $policy->auto_entry_note = implode("\n", $this->notes);
        $policy->auto_entry_date = date("Y-m-d");
        $policy->auto_entry_filename = $commissionUpload->name;
        
        if ($this->isValidIndex('comp_type')) {
            $policy->auto_entry_comp_type = $this->getProp('comp_type');
        }
        
        $policy->save();

        $this->policy = $policy;
        return true;        
    }

    private function isValidIndex(string $prop)
    {
        return isset($this->commissionData->$prop) && $this->commissionData->$prop !== null && $this->commissionData->$prop !== '';
    }

    private function getProp(string $prop)
    {
        $returnVal = trim($this->commissionData->$prop ?? "");
        // if($prop == 'group_no'){
        //     $valSpaces = explode(" ",$returnVal);
        //     if(sizeof($valSpaces) > 0){
        //         $returnVal = $valSpaces[0];
        //     }
        // }
        $returnVal = Utils::rowFormat($prop, $returnVal);


        return $returnVal;
    }
}
