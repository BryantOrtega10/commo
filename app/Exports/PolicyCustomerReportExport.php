<?php

namespace App\Exports;

use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PolicyCustomerReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $agent_number;
    protected $subscriber_name;
    protected $subscriber_date_birth;
    protected $city;
    protected $county;
    protected $carrier;
    protected $product;
    protected $plan_type;
    protected $product_type;
    protected $business_type;
    protected $business_segment;
    protected $policy_status;
    protected $app_submit_date_start;
    protected $app_submit_date_end;
    protected $app_id;
    protected $contract_num;
    protected $enrollment_method;
    protected $client_source;
    protected $request_effective_date_start;
    protected $request_effective_date_end;
    protected $original_effective_date_start;
    protected $original_effective_date_end;
    protected $benefit_effective_date_start;
    protected $benefit_effective_date_end;    
    protected $user;
    protected $entry_date_start;
    protected $entry_date_end;
    

    public function __construct(
        $agent_number,
        $subscriber_name,
        $subscriber_date_birth,
        $city,
        $county,
        $carrier,
        $product,
        $plan_type,
        $product_type,
        $business_type,
        $business_segment,
        $policy_status,
        $app_submit_date_start,
        $app_submit_date_end,
        $app_id,
        $contract_num,
        $enrollment_method,
        $client_source,
        $request_effective_date_start,
        $request_effective_date_end,
        $original_effective_date_start,
        $original_effective_date_end,
        $benefit_effective_date_start,
        $benefit_effective_date_end,
        $user,
        $entry_date_start,
        $entry_date_end,
    ) {
        $this->agent_number = $agent_number;
        $this->subscriber_name = $subscriber_name;
        $this->subscriber_date_birth = $subscriber_date_birth;
        $this->city = $city;
        $this->county = $county;
        $this->carrier = $carrier;
        $this->product = $product;
        $this->plan_type = $plan_type;
        $this->product_type = $product_type;
        $this->business_type = $business_type;
        $this->business_segment = $business_segment;
        $this->policy_status = $policy_status;
        $this->app_submit_date_start = $app_submit_date_start;
        $this->app_submit_date_end = $app_submit_date_end;
        $this->app_id = $app_id;
        $this->contract_num = $contract_num;
        $this->enrollment_method = $enrollment_method;
        $this->client_source = $client_source;
        $this->request_effective_date_start = $request_effective_date_start;
        $this->request_effective_date_end = $request_effective_date_end;
        $this->original_effective_date_start = $original_effective_date_start;
        $this->original_effective_date_end = $original_effective_date_end;
        $this->benefit_effective_date_start = $benefit_effective_date_start;
        $this->benefit_effective_date_end = $benefit_effective_date_end;
        $this->user = $user;
        $this->entry_date_start = $entry_date_start;
        $this->entry_date_end = $entry_date_end;
        
    }

    public function collection()
    {
        $policies = PoliciesModel::select("policies.*")
                                 ->join("products","products.id","=","policies.fk_product")
                                 ->join("customers","customers.id","=","policies.fk_customer")
                                 ->join("agent_numbers","agent_numbers.id","=","policies.fk_agent_number");
        
        if($this->agent_number !== null && $this->agent_number != ""){
            $policies->where("policies.fk_agent_number","=", $this->agent_number);
        }
        if($this->subscriber_name !== null && strlen($this->subscriber_name) >= 3){
            $subscriber_name = $this->subscriber_name;
            $policies->where(function($query) use($subscriber_name){
                $query->where("customers.first_name","like", '%'.$subscriber_name.'%')
                      ->orWhere("customers.last_name","like", '%'.$subscriber_name.'%');
            });
        }

        if($this->subscriber_date_birth !== null && $this->subscriber_date_birth != ""){
            $policies->where("customers.date_birth","=", $this->subscriber_date_birth);
        }
        if($this->city !== null && strlen($this->city) >= 3){
            $policies->where("customers.city","like", '%'.$this->city.'%');
        }
        if($this->county !== null && $this->county != ""){
            $policies->where("policies.fk_county","=", $this->county);
        }
        if($this->carrier !== null && $this->carrier != ""){
            $policies->where("products.fk_carrier","=", $this->carrier);
        }
        if($this->product !== null && $this->product != ""){
            $policies->where("policies.fk_product","=", $this->product);
        }
        if($this->plan_type !== null && $this->plan_type != ""){
            $policies->where("products.fk_plan_type","=", $this->plan_type);
        }
        if($this->product_type !== null && $this->product_type != ""){
            $policies->where("products.fk_product_type","=", $this->product_type);
        }
        if($this->business_type !== null && $this->business_type != ""){
            $policies->where("products.fk_business_type","=", $this->carrier);
        }
        if($this->business_segment !== null && $this->business_segment != ""){
            $policies->where("products.fk_business_segment","=", $this->business_segment);
        }
        if($this->policy_status !== null && $this->policy_status != ""){
            $policies->where("policies.fk_policy_status","=", $this->policy_status);
        }
        if($this->app_submit_date_start !== null && $this->app_submit_date_start != ""){
            $policies->where("policies.app_submit_date",">=", $this->app_submit_date_start);
        }
        if($this->app_submit_date_end !== null && $this->app_submit_date_end != ""){
            $policies->where("policies.app_submit_date","<=", $this->app_submit_date_end);
        }

        if($this->app_id !== null && $this->app_id != ""){
            $policies->where("policies.app_id","=", $this->app_id);
        }
        if($this->contract_num !== null && strlen($this->contract_num) >= 3){
            $policies->where("policies.contract_id","like", '%'.$this->contract_num.'%');
        }
        if($this->enrollment_method !== null && $this->enrollment_method != ""){
            $policies->where("policies.fk_enrollment_method","=", $this->enrollment_method);
        }
        if($this->client_source !== null && $this->client_source != ""){
            $policies->where("customers.fk_registration_s","=", $this->client_source);
        }
        if($this->request_effective_date_start !== null && $this->request_effective_date_start != ""){
            $policies->where("policies.original_effective_date",">=", $this->request_effective_date_start);
        }
        if($this->request_effective_date_end !== null && $this->request_effective_date_end != ""){
            $policies->where("policies.request_effective_date","<=", $this->request_effective_date_end);
        }
        
        if($this->original_effective_date_start !== null && $this->original_effective_date_start != ""){
            $policies->where("policies.original_effective_date",">=", $this->original_effective_date_start);
        }
        if($this->original_effective_date_end !== null && $this->original_effective_date_end != ""){
            $policies->where("policies.original_effective_date","<=", $this->original_effective_date_end);
        }
        if($this->benefit_effective_date_start !== null && $this->benefit_effective_date_start != ""){
            $policies->where("policies.benefit_effective_date",">=", $this->benefit_effective_date_start);
        }
        if($this->benefit_effective_date_end !== null && $this->benefit_effective_date_end != ""){
            $policies->where("policies.benefit_effective_date","<=", $this->benefit_effective_date_end);
        }
        if($this->user !== null && $this->user != ""){
            $policies->where("policies.fk_entry_user","=", $this->user);
        }
        
        if($this->entry_date_start !== null && $this->entry_date_start != ""){
            $policies->where("policies.created_at",">=", $this->entry_date_start);
        }
        if($this->entry_date_end !== null && $this->entry_date_end != ""){
            $policies->where("policies.created_at","<=", $this->entry_date_end);
        }
        
        
        return $policies->get();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->agent_number?->agent?->first_name.' '.$row->agent_number?->agent?->last_name,
            $row->agent_number?->number,
            $row->customer?->first_name.' '.$row->customer?->last_name,
            $row->contract_id,
            $row->product?->carrier?->name,
            $row->product?->product_type?->name,
            $row->product?->description,
            $row->agent_number_1?->agent?->first_name.' '.$row->agent_number_1?->agent?->last_name,
            $row->agent_number_1?->number,
            $row->agent_number_2?->agent?->first_name.' '.$row->agent_number_2?->agent?->last_name,
            $row->agent_number_2?->number,
            $row->product?->plan_type?->name,
            $row->product?->business_type?->name,
            $row->product?->business_segment?->name,
            $row->policy_status?->name,
            $row->premium,
            $row->app_submit_date ? date('m/d/Y', strtotime($row->app_submit_date)) : '',
            $row->application_id,
            $row->eligibility_id,
            $row->customer?->registration_s?->name,
            $row->enrollment_method?->name,
            $row->county?->name,
            $row->county?->region?->name,
            $row->product?->tier?->name,
            $row->original_effective_date ? date('m/d/Y', strtotime($row->original_effective_date)) : '',
            $row->benefit_effective_date ? date('m/d/Y', strtotime($row->benefit_effective_date)) : '',
            $row->cancel_date ? date('m/d/Y', strtotime($row->cancel_date)) : '',
            $row->num_adults,
            $row->num_dependents,
            $row->customer?->phone,
            $row->customer?->email,
            $row->created_at ? date('m/d/Y', strtotime($row->created_at)) : '',
            $row->entry_user?->name,            
            $row->customer->first_name." ".$row->last_name,
            $row->customer->date_birth ? date('m/d/Y', strtotime($row->date_birth)) : '',
            $row->customer->ssn,
            $row->customer->gender?->name,
            $row->customer->marital_status?->name,
            $row->customer->address,
            $row->customer->address_2,
            $row->customer->city,
            $row->customer->county?->state?->name,
            $row->customer->zip_code,
            $row->customer->county?->name,
            $row->customer->county?->region?->name,
            $row->customer->phone,
            $row->customer->phone_2,
            $row->customer->email,
            $row->customer->agent?->first_name. " ". $row->customer->agent?->last_name,
            $row->customer->first_name,
            $row->customer->middle_initial,
            $row->customer->last_name,
            $row->customer->suffix?->name,
            $row->customer->registration_s?->name,
            $row->customer->customer?->ssn." - ".$row->customer->customer?->first_name." ".$row->customer->customer?->last_name,
            $row->customer->status?->name,
            $row->customer->phase?->name,
            $row->customer->legal_basis?->name
        ];
    }

    public function headings(): array
    {
        return [
            'Policy Id',
            'Writing Agent Name',
            'Writing Agent #',
            'Subscriber',
            'Contract ID',
            'Carrier',
            'Product Type',
            'Plan Description',
            'Carrier 1 Agent Name',
            'Carrier 1 Agent #',
            'Carrier 2 Agent Name',
            'Carrier 2 Agent #',
            'Plan Type',
            'Business Type',
            'Business Segment',
            'Policy Status',
            'Premium',
            'App Submit',
            'App ID',
            'Eligibility Id',
            'Client Source',
            'Enrollment Method',
            'County',
            'Region',
            'Tier',
            'Original Effective',
            'Benefit Effective',
            'Cancel Date',
            '# Adults',
            '# Dependents',
            'Phone',
            'Email',
            'Entry Date',
            'Entry User',             
            "Full Name",
            "Date of Birth",
            "SSN",
            "Gender",
            "Marital Status",
            "Address",
            "Address 2",
            "City",
            "State",
            "Zip",
            "County",
            "Region",
            "Phone",
            "Phone 2",
            "Email",
            "Contact Agent",
            "First Name",
            "Middle Initial",
            "Last Name",
            "Suffix",
            "Registration Source",
            "Refering Customer",
            "Status",
            "Phase",
            "Legal Basis",
        ];



    }
}
