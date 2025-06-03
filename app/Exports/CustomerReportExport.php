<?php

namespace App\Exports;

use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsItemModel;
use App\Models\Customers\CustomersModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $date_birth_start;
    protected $date_birth_end;
    protected $city;
    protected $county;
    protected $contact_agent;

    public function __construct(
        $date_birth_start,
        $date_birth_end,
        $city,
        $county,
        $contact_agent,
    ) {
        $this->date_birth_start = $date_birth_start;
        $this->date_birth_end = $date_birth_end;
        $this->city = $city;
        $this->county = $county;
        $this->contact_agent = $contact_agent;
    }

    public function collection()
    {
        $customers = CustomersModel::select("customers.*",
                                            DB::raw("(SELECT count(policies.id) FROM policies WHERE policies.fk_customer = customers.id) as policy_count"));
                                   
        
        if($this->date_birth_start !== null && $this->date_birth_start != ""){
            $customers->where("customers.date_birth",">=", $this->date_birth_start);
        }
        if($this->date_birth_end !== null && $this->date_birth_end != ""){
            $customers->where("customers.date_birth","<=", $this->date_birth_end);
        }
        if($this->city !== null && $this->city != ""){
            $customers->where("customers.city","like", '%'.$this->city.'%');
        }
        if($this->county !== null && $this->county != ""){
            $customers->where("customers.fk_county","=", $this->county);
        }
        if($this->contact_agent !== null && $this->contact_agent != ""){
            $customers->where("customers.fk_agent","=", $this->contact_agent);
        }
        $customers->orderBy("first_name","ASC");
        $customers->orderBy("last_name","ASC");

        return $customers->get();
    }

    public function map($row): array
    {

        
        return [
            $row->first_name." ".$row->last_name,
            $row->date_birth ? date('m/d/Y', strtotime($row->date_birth)) : '',
            $row->ssn,
            $row->gender?->name,
            $row->marital_status?->name,
            $row->address,
            $row->address_2,
            $row->city,
            $row->county?->state?->name,
            $row->zip_code,
            $row->county?->name,
            $row->county?->region?->name,
            $row->phone,
            $row->phone_2,
            $row->email,
            $row->agent?->first_name. " ". $row->agent?->last_name,
            $row->first_name,
            $row->middle_initial,
            $row->last_name,
            $row->suffix?->name,
            $row->registration_s?->name,
            $row->customer?->ssn." - ".$row->customer?->first_name." ".$row->customer?->last_name,
            $row->status?->name,
            $row->phase?->name,
            $row->legal_basis?->name,
            $row->policy_count
        ];
    }

    public function headings(): array
    {
        return [
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
            "Policy Count",
        ];
    }
}
