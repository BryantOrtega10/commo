<?php

namespace App\Services\Commissions;

use App\Http\Controllers\Utils\Utils;
use App\Models\Agents\AgentNumAgentModel;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\CommissionRatesModel;
use App\Models\Commissions\CommissionTransactionsModel;
use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Commissions\CompensationTypesModel;
use App\Models\Commissions\StatementsItemModel;
use App\Models\Commissions\StatementsModel;
use App\Models\Customers\CuidsModel;
use App\Models\Customers\CustomersModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\CustomerStatusModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Policies\CountiesModel;
use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;


class CommissionRowProcessor
{
    private $commissionRow;
    private $commissionData;
    private $commissionTransaction;

    private $notes = [];

    private $subscriber = null;
    private $product = null;
    private $products = [];
    private $agentNumber = null;
    private $policy = null;

    public function startProcess(int $commissionRowId): void
    {
        $this->commissionRow = CommissionUploadRowsModel::with('commission_upload')->find($commissionRowId);
        $this->commissionRow->status = 1;
        $this->commissionRow->notes = "";
        $this->commissionRow->save();
        $this->commissionData = json_decode($this->commissionRow->data);
        $result = $this->process();

        if (sizeof($this->notes) > 0) {
            $this->commissionRow->notes = implode("\n", $this->notes);
        } else {
            $this->commissionRow->notes = "No problems found";
        }
        if ($result) {
            $this->commissionRow->status = 2;
            $this->commissionRow->commission_upload->increment('processed_rows');
        } else {
            $this->commissionRow->status = 3;
            $this->commissionRow->commission_upload->increment('error_rows');
        }
        $this->commissionRow->save();
    }


    protected function process(): bool
    {
        $this->findAgentNumber();
        $this->findSubscriber();
        $this->findProduct();

        if ($this->agentNumber === null) {
            array_push($this->notes, "Agent number not found");
            return false;
        }
        if ($this->policyIsNeed()) {
            $countPolicies = $this->findPolicy();
            if ($countPolicies == -1) {
                array_push($this->notes, "The policy found with the contract ID does not match the Product/Subscriber/AgentNumber");
                return false;
            }
            if ($countPolicies > 1) {
                array_push($this->notes, "More than one policy was found");
                return false;
            }
            if ($countPolicies == 0) {
                if (sizeof($this->products) > 0) {
                    $this->selectOneProduct();
                }
                array_push($this->notes, "Policy not found, trying to create it");
                if ($this->subscriber === null) {
                    array_push($this->notes, "Subscriber not found, trying to create it");
                    if (!$this->createSubscriber()) {
                        array_push($this->notes, "The subscriber could not be created");
                        return false;
                    }
                }
                if ($this->product === null) {
                    array_push($this->notes, "Product not found, trying to create it");
                    if (!$this->createProduct()) {
                        array_push($this->notes, "The product could not be created");
                        return false;
                    }
                }
                if (!$this->createPolicy()) {
                    array_push($this->notes, "The policy could not be created");
                    return false;
                }
            }
        }

        if (!$this->createTransaction()) {
            array_push($this->notes, "Can't create transaction item");
            return false;
        }

        if (!$this->createStatement()) {
            array_push($this->notes, "Can't create statements. No valid commission rates were found");
            return false;
        }

        if ($this->policy !== null) {
            $this->modifyPolicy();
        }
        return true;
    }

    protected function findSubscriber()
    {
        if ($this->isValidIndex('fb_uid')) {
            $queryVal = $this->getProp('fb_uid');
            $this->subscriber = CustomersModel::whereHas("cuids", function ($query) use ($queryVal) {
                $query->whereRaw("LOWER(name) = LOWER(?)", [$queryVal]);
            })
                ->first();
        }
        if ($this->subscriber === null && $this->isValidIndex('subscriber_last') && $this->isValidIndex('subscriber_first')) {
            $subscribers = CustomersModel::whereRaw("LOWER(first_name) = LOWER(?)", [$this->getProp('subscriber_first')])
                ->whereRaw("LOWER(first_name) = LOWER(?)", [$this->getProp('subscriber_last')])
                ->get();
            if (sizeof($subscribers) == 1) {
                $this->subscriber = $subscribers[0];
            }
        }
        if ($this->subscriber === null && $this->isValidIndex('group_name')) {
            $subscribers = CustomersModel::whereRaw("LOWER(first_name) = LOWER(?)", [$this->getProp('group_name')])->get();
            if (sizeof($subscribers) == 1) {
                $this->subscriber = $subscribers[0];
            }
        }
    }

    protected function selectOneProduct()
    {
        $tier = null;
        if ($this->isValidIndex('product_tier_metal_level')) {
            $tier = TiersModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('product_tier_metal_level')])->first();
        } elseif ($this->isValidIndex('product_tiermetal')) {
            $tier = TiersModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('product_tiermetal')])->first();            
        }

        foreach ($this->products as $itemProducto) {
            if(isset($tier) && $itemProducto->fk_tier == $tier->id){
                $this->product = $itemProducto;
                break;
            }
            else if(!isset($tier) && $itemProducto->fk_tier == null){
                $this->product = $itemProducto;
                break;
            }
        }
    }

    protected function findProduct()
    {

        $commissionUpload = $this->commissionRow->commission_upload;
        if ($this->isValidIndex('coverage') && $commissionUpload->fk_template == 2) { //Group
            $queryVal = $this->getProp('coverage');
            $products = ProductsModel::where(function ($query) use ($queryVal) {
                $query->whereHas("product_type", function ($query2) use ($queryVal) {
                    $query2->whereRaw("LOWER(name) = LOWER(?)", [$queryVal]);
                })
                    ->orWhereHas("product_alias", function ($query2) use ($queryVal) {
                        $query2->whereRaw("LOWER(alias) = LOWER(?)", [$queryVal]);
                    })
                    ->orWhere("description", "like", $queryVal . "%");
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
                    $query2->whereRaw("LOWER(name) = LOWER(?)", [$queryVal]);
                })
                    ->orWhereHas("product_alias", function ($query2) use ($queryVal) {
                        $query2->whereRaw("LOWER(alias) = LOWER(?)", [$queryVal]);
                    })
                    ->orWhere("description", "like", $queryVal . "%");
            })
                ->where(function ($query) {
                    $query->where("fk_business_type", "=", "1") //Individual
                        ->orWhereNull("fk_business_type");
                })
                ->where("fk_carrier", "=", $commissionUpload->fk_carrier);

            if ($this->isValidIndex('product_tier_metal_level')) {
                $tier = $this->getProp('product_tier_metal_level');
                $products->whereHas("tier", function ($query) use ($tier) {
                    $query->whereRaw("LOWER(name) = LOWER(?)", [$tier]);
                });
            }
            if ($this->isValidIndex('product_tiermetal')) {
                $tier = $this->getProp('product_tiermetal');
                $products->whereHas("tier", function ($query) use ($tier) {
                    $query->whereRaw("LOWER(name) = LOWER(?)", [$tier]);
                });
            }
            $products = $products->get();

            if (sizeof($products) == 1) {
                $this->product = $products[0];
            } else if (sizeof($products) > 1) {
                //dd($products, $this->commissionRow->id);
                $this->products = $products;
            }
        }
    }

    protected function findAgentNumber()
    {

        if ($this->isValidIndex('agent_code')) {
            $this->agentNumber = AgentNumbersModel::where("number", "=", $this->getProp('agent_code'))->first();
        }

        if ($this->agentNumber === null && $this->isValidIndex('agency_code') && $this->isValidIndex('wrt_agt')) {
            $number = $this->getProp('agency_code') . $this->getProp('wrt_agt');
            $this->agentNumber = AgentNumbersModel::where("number", "=", $number)->first();
        }
        if ($this->agentNumber === null && $this->isValidIndex('agy') && $this->isValidIndex('wrt_agt')) {
            $number = $this->getProp('agy') . $this->getProp('wrt_agt');
            $this->agentNumber = AgentNumbersModel::where("number", "=", $number)->first();
        }
        if ($this->agentNumber === null && $this->isValidIndex('agent_id')) {
            $this->agentNumber = AgentNumbersModel::where("number", "=", $this->getProp('agent_id'))->first();
        }
        if ($this->agentNumber === null && $this->isValidIndex('agent_last') && $this->isValidIndex('agent_first')) {

            $agent_last = $this->getProp('agent_last');
            $agent_first = $this->getProp('agent_first');
            $agentNumbers = AgentNumbersModel::whereHas("agent", function ($query) use ($agent_first, $agent_last) {
                $query->whereRaw("LOWER(first_name) = LOWER(?)", [$agent_first])
                    ->whereRaw("LOWER(last_name) = LOWER(?)", [$agent_last]);
            })->get();
            if (sizeof($agentNumbers) == 1) {
                $this->agentNumber = $agentNumbers[0];
            }
        }
        if ($this->agentNumber === null && $this->isValidIndex('agent_last_name') && $this->isValidIndex('agent_first_name')) {

            $agent_first = $this->getProp('agent_first_name');
            $agent_last = $this->getProp('agent_last_name');
            $agentNumbers = AgentNumbersModel::whereHas("agent", function ($query) use ($agent_first, $agent_last) {
                $query->whereRaw("LOWER(first_name) = LOWER(?)", [$agent_first])
                    ->whereRaw("LOWER(last_name) = LOWER(?)", [$agent_last]);
            })->get();
            if (sizeof($agentNumbers) == 1) {
                $this->agentNumber = $agentNumbers[0];
            }
        }
    }

    protected function findPolicy()
    {
        $hasContractID = false;
        $policies = PoliciesModel::select("policies.*");
        if ($this->isValidIndex('group_no')) {
            $policies->where("contract_id", "=", $this->getProp('group_no'));
            $hasContractID = true;
        } else if ($this->isValidIndex('member_contract_id')) {
            $policies->where("contract_id", "=", $this->getProp('member_contract_id'));
            $hasContractID = true;
        } else if ($this->isValidIndex('member_contract_no')) {
            $policies->where("contract_id", "=", $this->getProp('member_contract_no'));
            $hasContractID = true;
        }
        $beforeFilters = $policies->count();

        if ($this->subscriber !== null) {
            $policies->where("fk_customer", "=", $this->subscriber->id);
        }


        if (sizeof($this->products) > 0 && $hasContractID && $beforeFilters == 1) {
            $tempPolicy = $policies->first();

            foreach ($this->products as $itemProd) {
                if ($itemProd->id == $tempPolicy->fk_product) {
                    $this->product = $itemProd;
                    break;
                }
            }
        }

        if ($this->product !== null) {
            $policies->where("fk_product", "=", $this->product->id);
        }
        // $beforeFilters = $policies->count();
        // $tempPolicy = $policies->first();
        // dd($beforeFilters, $tempPolicy, $this->agentNumber);
        if ($this->agentNumber !== null) {
            $agentNumber = $this->agentNumber;
            $policies->where(function ($query) use ($agentNumber) {
                $query->where("fk_agent_number", "=", $this->agentNumber->id)
                    ->orWhere("fk_agent_number_1", "=", $this->agentNumber->id)
                    ->orWhere("fk_agent_number_2", "=", $this->agentNumber->id);
            });
        }
        $count = $policies->count();

        if ($hasContractID && $beforeFilters >= 1 && $count == 0) {
            //Encontro la poliza pero no coincide con el producto/subscriber/agent_number
            return -1;
        }

        if ($count == 1) {
            $this->policy = $policies->first();
        }

        return $count;
    }

    protected function createSubscriber()
    {
        $commissionUpload = $this->commissionRow->commission_upload;


        $customer = new CustomersModel();


        if ($commissionUpload->fk_template == 2) { //Group
            array_push($this->notes, "Template Group Found");
            if (!$this->isValidIndex('group_name')) {
                array_push($this->notes, "Group name not found");
                return false;
            }
            $customer->fk_business_type = 2;
            $customer->first_name = $this->getProp('group_name');
        } else {
            array_push($this->notes, "Template Individual Found");
            $firstName = "";
            $lastName = "";
            if ($this->isValidIndex('subscriber_first')) {
                $firstName = $this->getProp('subscriber_first');
            } elseif ($this->isValidIndex('subscriber_first_name')) {
                $firstName = $this->getProp('subscriber_first_name');
            } elseif ($this->isValidIndex('subscriber_name')) {
                $fullName = $this->getProp('subscriber_name');
                $fullName = explode(" ", $fullName);
                if (sizeof($fullName) > 0) {
                    $firstName = $fullName[0];
                }
            }

            if ($this->isValidIndex('subscriber_last')) {
                $lastName = $this->getProp('subscriber_last');
            } elseif ($this->isValidIndex('subscriber_last_name')) {
                $lastName = $this->getProp('subscriber_last_name');
            } elseif ($this->isValidIndex('subscriber_name')) {
                $fullName = $this->getProp('subscriber_name');
                $fullName = explode(" ", $fullName);
                if (sizeof($fullName) > 0) {
                    $lastName = last($fullName);
                }
            }

            if ($firstName == "" || $lastName == "") {
                return false;
            }
            $customer->fk_business_type = 1;
            $customer->first_name = $firstName;
            if ($this->isValidIndex('subscriber_middle')) {
                $customer->middle_initial = $this->getProp('subscriber_middle');
            } elseif ($this->isValidIndex('subscriber_middle_name')) {
                $customer->middle_initial = $this->getProp('subscriber_middle_name');
            }
            $customer->last_name = $lastName;
        }

        $customerStatus = CustomerStatusModel::whereRaw("LOWER(name) = 'customer'")->first();
        $customer->fk_status = $customerStatus->id;
        if ($this->agentNumber !== null) {
            $customer->fk_agent = $this->agentNumber->agent->id;
        }
        $customer->fk_entry_user = $commissionUpload->fk_entry_user;
        $customer->save();


        if ($this->isValidIndex('fb_uid')) {
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

    protected function createProduct()
    {
        $commissionUpload = $this->commissionRow->commission_upload;
        $product = new ProductsModel();

        $product->fk_carrier = $commissionUpload->fk_carrier;
        if ($commissionUpload->fk_template == 2) { //Group
            $product->fk_business_type = 2;
            if (!$this->isValidIndex('coverage')) {
                return false;
            }
            $product->description = $this->getProp('coverage');
            $productType = ProductTypesModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('coverage')])->first();
            if (!isset($productType)) {
                $productType = new ProductTypesModel();
                $productType->name = $this->getProp('coverage');
                $productType->save();
            }
            $product->fk_product_type = $productType->id;
        } else {
            if (!$this->isValidIndex('product_type')) {
                return false;
            }
            $product->description = $this->getProp('product_type');
            $productType = ProductTypesModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('product_type')])->first();
            if (!isset($productType)) {
                $productType = new ProductTypesModel();
                $productType->name = $this->getProp('product_type');
                $productType->save();
            }

            $product->fk_product_type = $productType->id;

            if ($this->isValidIndex('product_tier_metal_level')) {
                $tier = TiersModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('product_tier_metal_level')])->first();
                if (!isset($tier)) {
                    $tier = new TiersModel();
                    $tier->name = $this->getProp('product_tier_metal_level');
                    $tier->save();
                }
                $product->description .= " - " . $tier->name;
                $product->fk_tier = $tier->id;
            } elseif ($this->isValidIndex('product_tiermetal')) {
                $tier = TiersModel::whereRaw("LOWER(name) = LOWER(?)", [$this->getProp('product_tiermetal')])->first();
                if (!isset($tier)) {
                    $tier = new TiersModel();
                    $tier->name = $this->getProp('product_tiermetal');
                    $tier->save();
                }
                $product->description .= " - " . $tier->name;
                $product->fk_tier = $tier->id;
            }
            $product->fk_business_type = 1;
        }

        $product->fk_entry_user = $commissionUpload->fk_entry_user;
        $product->save();
        $this->product = $product;
        return true;
    }

    protected function createPolicy()
    {
        $commissionUpload = $this->commissionRow->commission_upload;
        $policy = new PoliciesModel();

        $policy->fk_customer = $this->subscriber->id;
        $policy->fk_agent_number = $this->agentNumber->id;
        $policy->fk_product = $this->product->id;
        if ($this->isValidIndex('county_name')) {
            $county = CountiesModel::where("name", "like", '%' . $this->getProp('county_name') . '%')->first();
            if (!isset($county)) {
                $county = new CountiesModel();
                $county->name = $this->getProp('county_name');
                $county->save();
            }
            $policy->fk_county = $county->id;
        }
        if ($commissionUpload->fk_template == 2) { //Group
            if (!$this->isValidIndex('group_no')) {
                return false;
            }
            $policy->contract_id = $this->getProp('group_no');
        } else {
            if (!$this->isValidIndex('member_contract_id') && !$this->isValidIndex('member_contract_no')) {
                return false;
            }
            if ($this->isValidIndex('member_contract_id')) {
                $policy->contract_id = $this->getProp('member_contract_id');
            }
            if ($this->isValidIndex('member_contract_no')) {
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
        } else {
            $policy->fk_policy_status = 1;
        }
        $policy->entry_method = 1;
        $policy->fk_entry_user = $commissionUpload->fk_entry_user;

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

    protected function createTransaction()
    {


        $commissionUpload = $this->commissionRow->commission_upload;

        $commissionTransaction = CommissionTransactionsModel::where("fk_comm_upload_row", "=", $this->commissionRow->id)->first();
        if (!isset($commissionTransaction)) {
            $commissionTransaction = new CommissionTransactionsModel();
        }
        $commissionTransaction->fk_comm_upload_row = $this->commissionRow->id;
        $commissionTransaction->check_date = $commissionUpload->check_date;
        if ($this->isValidIndex('compensation_date')) {
            $commissionTransaction->statement_date = $this->getProp('compensation_date');
        } else if ($this->isValidIndex('statement_date')) {
            $commissionTransaction->statement_date = $this->getProp('statement_date');
        } else {
            $commissionTransaction->statement_date = $commissionUpload->statement_date;
        }

        if (!isset($commissionTransaction->statement_date)) {

            return false;
        }

        $commissionTransaction->submit_date = date("Y-m-d");


        if ($this->isValidIndex('original_effective_date')) {
            $commissionTransaction->original_effective_date = $this->getProp('original_effective_date');
        }
        if ($this->isValidIndex('benefit_effective_date')) {
            $commissionTransaction->benefit_effective_date = $this->getProp('benefit_effective_date');
        }
        if ($this->isValidIndex('cancel_date')) {
            $commissionTransaction->cancel_date = $this->getProp('cancel_date');
        }
        if ($this->isValidIndex('initial_payment_date')) {
            $commissionTransaction->initial_payment_date = $this->getProp('initial_payment_date');
        }
        if ($this->isValidIndex('accounting_date')) {
            $commissionTransaction->accounting_date = $this->getProp('accounting_date');
        }

        if ($this->isValidIndex('compensation_amount') && is_numeric($this->getProp('compensation_amount'))) {
            $commissionTransaction->comp_amount = $this->getProp('compensation_amount');
        } elseif ($this->isValidIndex('comp_amount') && is_numeric($this->getProp('comp_amount'))) {
            $commissionTransaction->comp_amount = $this->getProp('comp_amount');
        } elseif ($this->isValidIndex('comm_amount') && is_numeric($this->getProp('comm_amount'))) {
            $commissionTransaction->comp_amount = $this->getProp('comm_amount');
        } else {

            return false;
        }

        if ($this->isValidIndex('comm_rateflat_rate') && is_numeric($this->getProp('comm_rateflat_rate'))) {
            $commissionTransaction->flat_rate = $this->getProp('comm_rateflat_rate');
        } elseif ($this->isValidIndex('flat_rate') && is_numeric($this->getProp('flat_rate'))) {
            $commissionTransaction->flat_rate = $this->getProp('flat_rate');
        }


        if ($this->isValidIndex('percent_of_prem') && is_numeric($this->getProp('percent_of_prem'))) {
            $commissionTransaction->premium_percentaje = $this->getProp('percent_of_prem');
        } elseif ($this->isValidIndex('percent_of_premium') && is_numeric($this->getProp('percent_of_premium'))) {
            $commissionTransaction->percent_of_premium = $this->getProp('percent_of_premium');
        }

        if ($this->isValidIndex('prem_paid_amt') && is_numeric($this->getProp('prem_paid_amt'))) {
            $commissionTransaction->premium_amount = $this->getProp('prem_paid_amt');
        } elseif ($this->isValidIndex('premium_from') && is_numeric($this->getProp('premium_from'))) {
            $commissionTransaction->premium_amount = $this->getProp('premium_from');
        }

        if ($this->isValidIndex('event_type')) {
            $commissionTransaction->event_type = $this->getProp('event_type');
        }

        if ($this->isValidIndex('exchange_ind')) {
            $commissionTransaction->exchange_ind = $this->getProp('exchange_ind');
        }
        $isAdjust = $this->policyIsNeed();
        $commissionTransaction->is_adjustment = $isAdjust;

        $commissionTransaction->comp_year = date("Y", strtotime($commissionTransaction->statement_date));
        if ($this->isValidIndex('is_qualified')) {
            $commissionTransaction->is_qualified = $this->getProp('is_qualified') == "YES";
        }

        if ($this->isValidIndex('comm_rateflat_rate')) {
            if (strpos($this->commissionData->comm_rateflat_rate, "%")) {
                $commissionTransaction->rate_type = 1;
            } elseif (strpos($this->commissionData->comm_rateflat_rate, "$")) {
                $commissionTransaction->rate_type = 2;
            }
        }

        if ($this->isValidIndex('agency_code')) {
            $agencyCode = AgencyCodesModel::where("name", "like", $this->getProp('agency_code') . "%")->first();
            if (isset($agencyCode)) {
                $commissionTransaction->fk_agency_code = $agencyCode->id;
            }
        }

        $commissionTransaction->fk_carrier = $commissionUpload->fk_carrier;

        if ($this->product !== null) {
            $commissionTransaction->fk_product = $this->product->id;
            $commissionTransaction->fk_business_segment = $this->product->fk_business_segment;
            $commissionTransaction->fk_business_type = $this->product->fk_business_type;
            $commissionTransaction->fk_plan_type = $this->product->fk_plan_type;
            $commissionTransaction->fk_product_type = $this->product->fk_product_type;
            $commissionTransaction->fk_tier = $this->product->fk_tier;
        }

        if ($this->isValidIndex('comp_type')) {
            $name = $this->getProp('comp_type');
            $compensationType = CompensationTypesModel::where("name", "=", $name)->first();
            if (!isset($compensationType)) {
                $compensationType = CompensationTypesModel::create(["name" => $name, "description" => $name, "sort_order" => "-1", "status" => "1"]);
            }
            $commissionTransaction->fk_compensation_type = $compensationType->id;
        }

        if ($this->isValidIndex('county_name')) {
            $county = CountiesModel::where("name", "like", '%' . $this->getProp('county_name') . '%')->first();
            if (!isset($county)) {
                $county = new CountiesModel();
                $county->name = $this->getProp('county_name');
                $county->save();
            }
            $commissionTransaction->fk_county = $county->id;
        }

        if ($this->policy !== null) {
            $commissionTransaction->fk_policy = $this->policy->id;
        }

        if ($this->agentNumber !== null) {
            $commissionTransaction->fk_agent_number = $this->agentNumber->id;
        }
        if (!$this->policyIsNeed()) {
            $commissionTransaction->adjusment_subscriber = $this->getProp("subscriber_name");
        }

        $commissionTransaction->notes = implode("\n", $this->notes);
        $commissionTransaction->fk_entry_user = $commissionUpload->fk_entry_user;
        $commissionTransaction->save();

        $this->commissionTransaction = $commissionTransaction;

        return true;
    }

    protected function createStatement()
    {

        if (isset($this->commissionTransaction->is_qualified) && $this->commissionTransaction->is_qualified == false) {
            return true;
        }

        $agent_number = AgentNumbersModel::find($this->commissionTransaction->fk_agent_number);
        if (isset($this->commissionTransaction->fk_policy)) {
            $agent_number = AgentNumbersModel::find($this->commissionTransaction->policy->fk_agent_number);
        }
        StatementsItemModel::where("fk_commission_transaction", "=", $this->commissionTransaction->id)->delete();
        $countStatements = $this->createStatementAgent($agent_number, 0);

        if ($countStatements == 0) {
            return false;
        }

        return true;
    }

    protected function createStatementAgent(AgentNumbersModel $agentNumber, $agent_type)
    {
        $commissionRates = CommissionRatesModel::select("commission_rates.*")->where("fk_agent_number", "=", $agentNumber->id);
        if (isset($this->commissionTransaction->fk_business_segment)) {
            $queryTxt = $this->commissionTransaction->fk_business_segment;
            //dump("fk_business_segment",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_business_segment", "=", $queryTxt)
                    ->orWhereNull("fk_business_segment");
            });
        }

        if (isset($this->commissionTransaction->fk_business_type)) {
            $queryTxt = $this->commissionTransaction->fk_business_type;
            //dump("fk_business_type",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_business_type", "=", $queryTxt)
                    ->orWhereNull("fk_business_type");
            });
        }

        if (isset($this->commissionTransaction->fk_compensation_type)) {
            $queryTxt = $this->commissionTransaction->fk_compensation_type;
            //dump("fk_compensation_type",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_compensation_type", "=", $queryTxt)
                    ->orWhereNull("fk_compensation_type");
            });
        }

        if (isset($this->commissionTransaction->fk_amf_compensation_type)) {
            $queryTxt = $this->commissionTransaction->fk_amf_compensation_type;
            //dump("fk_amf_compensation_type",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_amf_compensation_type", "=", $queryTxt)
                    ->orWhereNull("fk_amf_compensation_type");
            });
        }

        if (isset($this->commissionTransaction->fk_plan_type)) {
            $queryTxt = $this->commissionTransaction->fk_plan_type;
            //dump("fk_plan_type",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_plan_type", "=", $queryTxt)
                    ->orWhereNull("fk_plan_type");
            });
        }

        if (isset($this->commissionTransaction->fk_product)) {
            $queryTxt = $this->commissionTransaction->fk_product;
            //dump("fk_product",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_product", "=", $queryTxt)
                    ->orWhereNull("fk_product");
            });
        }

        if (isset($this->commissionTransaction->fk_product_type)) {
            $queryTxt = $this->commissionTransaction->fk_product_type;
            //dump("fk_product_type",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_product_type", "=", $queryTxt)
                    ->orWhereNull("fk_product_type");
            });
        }

        if (isset($this->commissionTransaction->fk_tier)) {
            $queryTxt = $this->commissionTransaction->fk_tier;
            //dump("fk_tier",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_tier", "=", $queryTxt)
                    ->orWhereNull("fk_tier");
            });
        }

        if (isset($this->commissionTransaction->fk_county)) {
            $queryTxt = $this->commissionTransaction->fk_county;
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("fk_county", "=", $queryTxt)
                    ->orWhereNull("fk_county");
            });
            //dump("fk_county",$queryTxt);
            $query2Txt = $this->commissionTransaction->county?->fk_region;
            //dump("fk_region",$query2Txt);
            $commissionRates->where(function ($query) use ($query2Txt) {
                $query->where("fk_region", "=", $query2Txt)
                    ->orWhereNull("fk_region");
            });
        }


        if (isset($this->commissionTransaction->fk_policy)) {
            $queryTxt = $this->commissionTransaction->policy->contract_id;
            //dump("contract_id",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("policy_contract_id", "=", $queryTxt)
                    ->orWhereNull("policy_contract_id");
            });
        }

        if (isset($this->commissionTransaction->submit_date)) {
            $queryTxt = $this->commissionTransaction->submit_date;
            //dump("submit_date",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("submit_from", "<=", $queryTxt)
                    ->orWhereNull("submit_from");
            });

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("submit_to", ">=", $queryTxt)
                    ->orWhereNull("submit_to");
            });
        }

        if (isset($this->commissionTransaction->statement_date)) {
            $queryTxt = $this->commissionTransaction->statement_date;
            //dump("statement_date",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("statement_from", "<=", $queryTxt)
                    ->orWhereNull("statement_from");
            });

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("statement_to", ">=", $queryTxt)
                    ->orWhereNull("statement_to");
            });
        }

        if (isset($this->commissionTransaction->original_effective_date)) {
            $queryTxt = $this->commissionTransaction->original_effective_date;
            //dump("original_effective_date",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("original_effective_from", "<=", $queryTxt)
                    ->orWhereNull("original_effective_from");
            });

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("original_effective_to", ">=", $queryTxt)
                    ->orWhereNull("original_effective_to");
            });
        }

        if (isset($this->commissionTransaction->original_effective_date)) {
            $queryTxt = $this->commissionTransaction->original_effective_date;
            //dump("original_effective_date",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("original_effective_from", "<=", $queryTxt)
                    ->orWhereNull("original_effective_from");
            });

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("original_effective_to", ">=", $queryTxt)
                    ->orWhereNull("original_effective_to");
            });
        }


        if (isset($this->commissionTransaction->benefit_effective_date)) {
            $queryTxt = $this->commissionTransaction->benefit_effective_date;
            //dump("benefit_effective_date",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("benefit_effective_from", "<=", $queryTxt)
                    ->orWhereNull("benefit_effective_from");
            });

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("benefit_effective_to", ">=", $queryTxt)
                    ->orWhereNull("benefit_effective_to");
            });
        }

        if (isset($this->commissionTransaction->flat_rate)) {
            $queryTxt = $this->commissionTransaction->flat_rate;
            //dump("flat_rate",$queryTxt);
            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("flat_rate", "=", $queryTxt)
                    ->orWhereNull("flat_rate");
            });
        }

        if (isset($this->commissionTransaction->rate_type)) {
            $queryTxt = $this->commissionTransaction->rate_type;
            //dump("rate_type",$queryTxt);

            $commissionRates->where(function ($query) use ($queryTxt) {
                $query->where("rate_type", "=", $queryTxt)
                    ->orWhereNull("rate_type");
            });
        }

        $commissionRates->orderBy("order", "ASC");
        $comissionRate = $commissionRates->first();
        //dd($comissionRate);
        //Pregunta: Si no se encuentra un commission rate no se le paga a este agente y los demas agentes relacionados?
        if (!isset($comissionRate)) {
            return 0;
        }

        $commissionUpload = $this->commissionRow->commission_upload;
        $comp_amount = $this->commissionTransaction->comp_amount;

        if ($comissionRate->rate_type == 1 || !isset($comissionRate->rate_type)) {
            $comp_amount = $comp_amount * $comissionRate->rate_amount;
        } elseif ($comissionRate->rate_type == 2) {
            $comp_amount = $this->commissionTransaction->flat_rate;
        } elseif ($comissionRate->rate_type == 3) {

            $member_count = 1;
            if ($this->isValidIndex('member_count')) {
                $member_count = intval($this->getProp('member_count'));
            } else if (isset($this->commissionTransaction->fk_policy)) {
                $member_count = $this->commissionTransaction->policy->num_dependents;
            }
            if (empty($member_count)) {
                $member_count = 1;
            }

            $comp_amount = $this->commissionTransaction->flat_rate * $member_count;
        }


        $statement = StatementsModel::where("statement_date", "=", $this->commissionTransaction->statement_date)
            ->where("fk_agent_number", "=", $agentNumber->id)
            ->where("status", "=", "0")
            ->first();
            
        if (!isset($statement)) {
            $statement = new StatementsModel();
            $statement->statement_date = $this->commissionTransaction->statement_date;
            $statement->fk_agent_number = $agentNumber->id;
            $statement->save();
        }



        $statementItem = new StatementsItemModel();
        $statementItem->check_date = $this->commissionTransaction->check_date;
        $statementItem->fk_statement = $statement->id;
        $statementItem->agent_type = $agent_type;
        $statementItem->flat_rate = $comissionRate->rate_amount;
        $statementItem->rate_type = $comissionRate->rate_type;
        $statementItem->comp_amount = $comp_amount;
        $statementItem->fk_commission_rate = $comissionRate->id;
        $statementItem->fk_commission_transaction = $this->commissionTransaction->id;
        $statementItem->fk_entry_user = $commissionUpload->fk_entry_user;
        $statementItem->save();

        $queryTxt = $statement->statement_date;
        $agentNumbers = AgentNumAgentModel::where("fk_agent_number_base", "=", $agentNumber->id)
            ->where(function ($query) use ($queryTxt) {
                $query->where("start_date", "<=", $queryTxt)
                    ->orWhereNull("start_date");
            })
            ->where(function ($query) use ($queryTxt) {
                $query->where("end_date", ">=", $queryTxt)
                    ->orWhereNull("end_date");
            })
            ->get();
        $countStatementItem = 1;
        foreach ($agentNumbers as $itemAgentNumber) {
            $countStatementItem += $this->createStatementAgent($itemAgentNumber->agent_number_rel, $itemAgentNumber->type);
        }

        return $countStatementItem;
    }

    protected function modifyPolicy()
    {
        if ($this->isValidIndex('cancel_date')) {
            $this->policy->cancel_date = $this->getProp('cancel_date');
            $this->policy->fk_policy_status = 2;
        }

        if ($this->isValidIndex('event_type')) {
            if ($this->getProp('event_type') == "CANCEL") {
                $this->policy->fk_policy_status = 2;
            }
        }

        $this->policy->increment('count_commisions');
        $this->policy->save();
    }

    private function policyIsNeed()
    {
        $policyIsNeed = true;
        $commissionWithoutPolicy = ["Prior Balance", "Adjustment"];
        if ($this->isValidIndex('comp_type') && in_array($this->getProp("comp_type"), $commissionWithoutPolicy)) {
            $policyIsNeed = false;
        }
        return $policyIsNeed;
    }

    private function isValidIndex(string $prop)
    {
        return isset($this->commissionData->$prop) && $this->commissionData->$prop !== null && $this->commissionData->$prop !== '';
    }

    private function getProp(string $prop)
    {
        $returnVal = trim($this->commissionData->$prop ?? "");
        $returnVal = str_replace("$", "", $returnVal);
        $returnVal = str_replace("%", "", $returnVal);
        // if($prop == 'group_no'){
        //     $valSpaces = explode(" ",$returnVal);
        //     if(sizeof($valSpaces) > 0){
        //         $returnVal = $valSpaces[0];
        //     }
        // }
        $returnVal = Utils::dbFormat($prop, $returnVal);


        return $returnVal;
    }
}
