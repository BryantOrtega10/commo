<?php

namespace App\Exports;

use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\StatementsItemModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgentReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $agency_code;
    protected $agent_status;
    protected $mentor_agent_number;
    protected $override_agent_number;
    protected $sales_region;
    protected $agent_title;

    public function __construct(
        $agency_code,
        $agent_status,
        $mentor_agent_number,
        $override_agent_number,
        $sales_region,
        $agent_title
    ) {
        $this->agency_code = $agency_code;
        $this->agent_status = $agent_status;
        $this->mentor_agent_number = $mentor_agent_number;
        $this->override_agent_number = $override_agent_number;
        $this->sales_region = $sales_region;
        $this->agent_title = $agent_title;
    }

    public function collection()
    {
        $agents = AgentNumbersModel::select("agent_numbers.*")->join("agents", "agents.id", "=", "agent_numbers.fk_agent");
        
        if($this->agency_code !== null && $this->agency_code != ""){
            $agents->where("agent_numbers.fk_agency_code","=", $this->agency_code);
        }
        if($this->agent_status !== null && $this->agent_status != ""){
            $agents->where("agent_numbers.fk_agent_status","=", $this->agent_status);
        }
        if($this->mentor_agent_number !== null && $this->mentor_agent_number != ""){
            $mentorNumber = $this->mentor_agent_number;
            $agents->whereHas("mentor_agents", function ($query) use($mentorNumber) {
                $query->where("fk_agent_number_rel","=",$mentorNumber);
            });
        }
        if($this->override_agent_number !== null && $this->override_agent_number != ""){
            $overrideNumber = $this->override_agent_number;
            $agents->whereHas("override_agents", function ($query) use($overrideNumber) {
                $query->where("fk_agent_number_rel","=",$overrideNumber);
            });
        }
        if($this->sales_region !== null && $this->sales_region != ""){
            $agents->where("agents.fk_sales_region","=", $this->sales_region);
        }
        if($this->agent_title !== null && $this->agent_title != ""){
            $agents->where("agent_numbers.fk_agent_title","=", $this->agent_title);
        }


        return $agents->get();
    }

    public function map($row): array
    {

        $override_agents = $row->override_agents;
        $mentor_agents = $row->mentor_agents;

        return [
            $row->agent->first_name." ".$row->agent->last_name,
            $row->agent->date_birth ? date('m/d/Y', strtotime($row->agent->date_birth)) : '',
            $row->agent->ssn,
            $row->agent->payroll_emp_ID,
            $row->agent->company_EIN,
            $row->agent->contract_type?->name ?? "",
            $row->agent->gender?->name ?? "",
            $row->agent->address,
            $row->agent->address_2,
            $row->agent->city,
            $row->agent->state?->name ?? "",
            $row->agent->zip_code,
            $row->agent->phone,
            $row->agent->phone_2,
            $row->agent->email,
            $row->agent->national_producer,
            $row->agent->license_number,
            $row->agent->sales_region?->name ?? "",
            $row->agent->contract_date ? date('m/d/Y', strtotime($row->agent->contract_date)) : '',
            $row->agent->has_CMS ? "YES" : "NO",
            $row->agent->CMS_date ? date('m/d/Y', strtotime($row->agent->CMS_date)) : '',
            $row->agent->has_marketplace_cert ? "YES" : "NO",
            $row->agent->marketplace_cert_date ? date('m/d/Y', strtotime($row->agent->marketplace_cert_date)) : '',
            $row->agent->created_at ? date('m/d/Y', strtotime($row->agent->created_at)) : '',
            $row->agent->entry_user?->name ?? "",
            $row->number,
            $row->agency_code?->name ?? "",
            $row->carrier?->name ?? "",
            $row->agent_title?->name ?? "",
            $row->agent_status?->name ?? "",
            $row->agency?->name ?? "",
            $row->contract_rate,
            $row->admin_fee?->name ?? "",
            isset($override_agents[0]) ? $override_agents[0]->agent_number_rel?->agent?->first_name." ".$override_agents[0]->agent_number_rel?->agent?->last_name : '',
            isset($override_agents[0]) ? $override_agents[0]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($override_agents[0]) ? $override_agents[0]->agent_number_rel?->number ?? "" : '',
            isset($override_agents[0]) ? $override_agents[0]->start_date : '',
            isset($override_agents[0]) ? $override_agents[0]->end_date : '',
            
            isset($override_agents[1]) ? $override_agents[1]->agent_number_rel?->agent?->first_name." ".$override_agents[1]->agent_number_rel?->agent?->last_name : '',
            isset($override_agents[1]) ? $override_agents[1]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($override_agents[1]) ? $override_agents[1]->agent_number_rel?->number ?? "" : '',
            isset($override_agents[1]) ? $override_agents[1]->start_date : '',
            isset($override_agents[1]) ? $override_agents[1]->end_date : '',

            isset($override_agents[2]) ? $override_agents[2]->agent_number_rel?->agent?->first_name." ".$override_agents[2]->agent_number_rel?->agent?->last_name : '',
            isset($override_agents[2]) ? $override_agents[2]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($override_agents[2]) ? $override_agents[2]->agent_number_rel?->number ?? "" : '',
            isset($override_agents[2]) ? $override_agents[2]->start_date : '',
            isset($override_agents[2]) ? $override_agents[2]->end_date : '',

            isset($override_agents[3]) ? $override_agents[3]->agent_number_rel?->agent?->first_name." ".$override_agents[3]->agent_number_rel?->agent?->last_name : '',
            isset($override_agents[3]) ? $override_agents[3]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($override_agents[3]) ? $override_agents[3]->agent_number_rel?->number ?? "" : '',
            isset($override_agents[3]) ? $override_agents[3]->start_date : '',
            isset($override_agents[3]) ? $override_agents[3]->end_date : '',

            isset($override_agents[4]) ? $override_agents[4]->agent_number_rel?->agent?->first_name." ".$override_agents[4]->agent_number_rel?->agent?->last_name : '',
            isset($override_agents[4]) ? $override_agents[4]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($override_agents[4]) ? $override_agents[4]->agent_number_rel?->number ?? "" : '',
            isset($override_agents[4]) ? $override_agents[4]->start_date : '',
            isset($override_agents[4]) ? $override_agents[4]->end_date : '',

            isset($mentor_agents[0]) ? $mentor_agents[0]->agent_number_rel?->agent?->first_name." ".$mentor_agents[0]->agent_number_rel?->agent?->last_name : '',
            isset($mentor_agents[0]) ? $mentor_agents[0]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($mentor_agents[0]) ? $mentor_agents[0]->agent_number_rel?->number ?? "" : '',
            isset($mentor_agents[0]) ? $mentor_agents[0]->start_date : '',
            isset($mentor_agents[0]) ? $mentor_agents[0]->end_date : '',
            
            isset($mentor_agents[1]) ? $mentor_agents[1]->agent_number_rel?->agent?->first_name." ".$mentor_agents[1]->agent_number_rel?->agent?->last_name : '',
            isset($mentor_agents[1]) ? $mentor_agents[1]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($mentor_agents[1]) ? $mentor_agents[1]->agent_number_rel?->number ?? "" : '',
            isset($mentor_agents[1]) ? $mentor_agents[1]->start_date : '',
            isset($mentor_agents[1]) ? $mentor_agents[1]->end_date : '',

            isset($mentor_agents[2]) ? $mentor_agents[2]->agent_number_rel?->agent?->first_name." ".$mentor_agents[2]->agent_number_rel?->agent?->last_name : '',
            isset($mentor_agents[2]) ? $mentor_agents[2]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($mentor_agents[2]) ? $mentor_agents[2]->agent_number_rel?->number ?? "" : '',
            isset($mentor_agents[2]) ? $mentor_agents[2]->start_date : '',
            isset($mentor_agents[2]) ? $mentor_agents[2]->end_date : '',

            isset($mentor_agents[3]) ? $mentor_agents[3]->agent_number_rel?->agent?->first_name." ".$mentor_agents[3]->agent_number_rel?->agent?->last_name : '',
            isset($mentor_agents[3]) ? $mentor_agents[3]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($mentor_agents[3]) ? $mentor_agents[3]->agent_number_rel?->number ?? "" : '',
            isset($mentor_agents[3]) ? $mentor_agents[3]->start_date : '',
            isset($mentor_agents[3]) ? $mentor_agents[3]->end_date : '',

            isset($mentor_agents[4]) ? $mentor_agents[4]->agent_number_rel?->agent?->first_name." ".$mentor_agents[4]->agent_number_rel?->agent?->last_name : '',
            isset($mentor_agents[4]) ? $mentor_agents[4]->agent_number_rel?->carrier?->name ?? "" : '',
            isset($mentor_agents[4]) ? $mentor_agents[4]->agent_number_rel?->number ?? "" : '',
            isset($mentor_agents[4]) ? $mentor_agents[4]->start_date : '',
            isset($mentor_agents[4]) ? $mentor_agents[4]->end_date : '',

            $row->created_at ? date('m/d/Y', strtotime($row->created_at)) : '',
            $row->entry_user?->name ?? "",
        ];
    }

    public function headings(): array
    {
        return [
            'Writing Agent',
            'Date of Birth',
            'SSN',
            'Payroll ID',
            'Company EIN',
            'Contract Type',
            'Gender',
            'Address',
            'Address 2',
            'City',
            'State',
            'Zip',
            'Phone',
            'Phone 2',
            'Email',
            'National Producer #',
            'License Number',
            'Sales Region',
            'Contract Date',
            'CMS Certification',
            'CMS Date',
            'Marketplace Certification',
            'Marketplace Date',
            'Agent Entry Date',
            'Agent Entry User',
            'Agent Number',
            'Agency Code',
            'Carrier',
            'Agent Title',
            'Agent Status',
            'Pay To Agency',
            'Contract Rate',
            'Admin Fee Schedule',
            'Override Agent 1',
            'Override Agent 1 Carrier',
            'Override Agent 1 Number',
            'Override Agent 1 Start Date',
            'Override Agent 1 End Date',
            'Override Agent 2',
            'Override Agent 2 Carrier',
            'Override Agent 2 Number',
            'Override Agent 2 Start Date',
            'Override Agent 2 End Date',
            'Override Agent 3',
            'Override Agent 3 Carrier',
            'Override Agent 3 Number',
            'Override Agent 3 Start Date',
            'Override Agent 3 End Date',
            'Override Agent 4',
            'Override Agent 4 Carrier',
            'Override Agent 4 Number',
            'Override Agent 4 Start Date',
            'Override Agent 4 End Date',
            'Override Agent 5',
            'Override Agent 5 Carrier',
            'Override Agent 5 Number',
            'Override Agent 5 Start Date',
            'Override Agent 5 End Date',
            'Mentor Agent 1',
            'Mentor Agent 1 Carrier',
            'Mentor Agent 1 Number',
            'Mentor Agent 1 Start Date',
            'Mentor Agent 1 End Date',
            'Mentor Agent 2',
            'Mentor Agent 2 Carrier',
            'Mentor Agent 2 Number',
            'Mentor Agent 2 Start Date',
            'Mentor Agent 2 End Date',
            'Mentor Agent 3',
            'Mentor Agent 3 Carrier',
            'Mentor Agent 3 Number',
            'Mentor Agent 3 Start Date',
            'Mentor Agent 3 End Date',
            'Mentor Agent 4',
            'Mentor Agent 4 Carrier',
            'Mentor Agent 4 Number',
            'Mentor Agent 4 Start Date',
            'Mentor Agent 4 End Date',
            'Mentor Agent 5',
            'Mentor Agent 5 Carrier',
            'Mentor Agent 5 Number',
            'Mentor Agent 5 Start Date',
            'Mentor Agent 5 End Date',
            'Agent Number Entry Date',
            'Agent Number Entry User'
        ];
    }
}
