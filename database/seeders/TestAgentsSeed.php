<?php

namespace Database\Seeders;

use App\Models\Agents\AgentNumAgentModel;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAgentsSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAgent1 = User::create([
            'name' => 'Agent Test',
            'email' => 'bryant.ortega1010@gmail.com',
            'password' => Hash::make("1900"),
            'role' => 'agent'
        ]);

        $userAgent2 = User::create([
            'name' => 'Diego Pineda',
            'email' => 'webmaster@mdccolombia.com',
            'password' => Hash::make("1900"),
            'role' => 'agent'
        ]);

        $agent1 = AgentsModel::create([
            "first_name" => "Agent",
            "last_name" => "Test",
            "date_birth" => "1994-10-10",
            "ssn" => "1900",
            "fk_gender" => "2",
            "email" => "bryant.ortega1010@gmail.com",
            "fk_state" => "1",
            "city" => "Bogotá",
            "zip_code" => "111001",
            "fk_sales_region" => "1",
            "has_CMS" => false,
            "has_marketplace_cert" => true,
            "marketplace_cert_date" => "2025-01-01",
            "contract_date" => "2023-07-03",
            "payroll_emp_ID" => "123",
            "fk_contract_type" => "1",
            "fk_entry_user" => "1",
            "fk_user" => $userAgent1->id
        ]);


        $agent2 = AgentsModel::create([
            "first_name" => "Diego",
            "last_name" => "Pineda",
            "date_birth" => "1984-06-04",
            "ssn" => "987645",
            "fk_gender" => "2",
            "email" => "webmaster@mdccolombia.com",
            "fk_state" => "1",
            "city" => "Bogotá",
            "zip_code" => "111001",
            "fk_sales_region" => "1",
            "has_CMS" => false,
            "has_marketplace_cert" => true,
            "marketplace_cert_date" => "2024-01-01",
            "contract_date" => "2010-01-01",
            "payroll_emp_ID" => "1234",
            "fk_contract_type" => "1",
            "fk_entry_user" => "1",
            "fk_user" => $userAgent2->id
        ]);

        $agentNumer1 = AgentNumbersModel::create([
            "number" => "1000001",
            "fk_agency_code" => "1",
            "fk_carrier" => "1",
            "fk_agent_title" => "1",
            "fk_agent_status" => "1",
            "fk_agency" => "1",
            "contract_rate" => "0.95",
            "fk_admin_fee" => "1",
            "fk_entry_user" => "1",
            "fk_agent" => $agent1->id
        ]);

        $agentNumer2 = AgentNumbersModel::create([
            "number" => "1000002",
            "fk_agency_code" => "2",
            "fk_carrier" => "2",
            "fk_agent_title" => "2",
            "fk_agent_status" => "2",
            "fk_agency" => "2",
            "contract_rate" => "0.99",
            "fk_admin_fee" => "2",
            "fk_entry_user" => "1",
            "fk_agent" => $agent1->id
        ]);

        AgentNumAgentModel::create([
            "type" => "1",
            "fk_agent_number" => $agentNumer1->id,
            "fk_agent" => $agent2->id,
        ]);
    }
}
