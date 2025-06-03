<?php

namespace App\Exports;

use App\Models\Policies\PoliciesModel;
use App\Models\Products\ProductsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PolicyReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $app_submit_date_start;
    protected $app_submit_date_end;
    protected $original_effective_date_start;
    protected $original_effective_date_end;
    protected $benefit_effective_date_start;
    protected $benefit_effective_date_end;
    protected $client_source;
    protected $agent_number;
    protected $business_segment;
    protected $business_type;
    protected $carrier;
    protected $plan_type;
    protected $description;
    protected $product_type;

    public function __construct(
        $app_submit_date_start,
        $app_submit_date_end,
        $original_effective_date_start,
        $original_effective_date_end,
        $benefit_effective_date_start,
        $benefit_effective_date_end,
        $client_source,
        $agent_number,
        $business_segment,
        $business_type,
        $carrier,
        $plan_type,
        $description,
        $product_type,
    ) {
        $this->app_submit_date_start = $app_submit_date_start;
        $this->app_submit_date_end = $app_submit_date_end;
        $this->original_effective_date_start = $original_effective_date_start;
        $this->original_effective_date_end = $original_effective_date_end;
        $this->benefit_effective_date_start = $benefit_effective_date_start;
        $this->benefit_effective_date_end = $benefit_effective_date_end;
        $this->client_source = $client_source;
        $this->agent_number = $agent_number;
        $this->business_segment = $business_segment;
        $this->business_type = $business_type;
        $this->carrier = $carrier;
        $this->plan_type = $plan_type;
        $this->description = $description;
        $this->product_type = $product_type;
        
    }

    public function collection()
    {
        $policies = PoliciesModel::select("policies.*")
                                 ->join("products","products.id","=","policies.fk_product")
                                 ->join("customers","customers.id","=","policies.fk_customer")
                                 ->join("agent_numbers","agent_numbers.id","=","policies.fk_agent_number");
                                 
        if($this->app_submit_date_start !== null && $this->app_submit_date_start != ""){
            $policies->where("policies.app_submit_date",">=", $this->app_submit_date_start);
        }
        if($this->app_submit_date_end !== null && $this->app_submit_date_end != ""){
            $policies->where("policies.app_submit_date","<=", $this->app_submit_date_end);
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
        if($this->client_source !== null && $this->client_source != ""){
            $policies->where("customers.fk_registration_s","=", $this->client_source);
        }
        if($this->agent_number !== null && $this->agent_number != ""){
            $policies->where("agent_numbers.number","=", $this->agent_number);
        }
        if($this->business_segment !== null && $this->business_segment != ""){
            $policies->where("products.fk_business_segment","=", $this->business_segment);
        }
        if($this->carrier !== null && $this->carrier != ""){
            $policies->where("products.fk_carrier","=", $this->carrier);
        }
        if($this->business_type !== null && $this->business_type != ""){
            $policies->where("products.fk_business_type","=", $this->carrier);
        }
        if($this->description !== null && $this->description != ""){
            $policies->where("products.description","like", '%'.$this->description.'%');
        }
        if($this->plan_type !== null && $this->plan_type != ""){
            $policies->where("products.fk_plan_type","=", $this->plan_type);
        }
        if($this->product_type !== null && $this->product_type != ""){
            $policies->where("products.fk_product_type","=", $this->product_type);
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
            $row->customer?->first_name,
            $row->customer?->middle_initial,
            $row->customer?->last_name,
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
            'First',
            'Middle',
            'Last',
        ];



    }
}
