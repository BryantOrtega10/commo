<?php

namespace Database\Seeders;

use App\Models\Agents\AgentNumAgentModel;
use App\Models\Agents\AgentNumbersModel;
use App\Models\Agents\AgentsModel;
use App\Models\MultiTable\AdminFeesModel;
use App\Models\MultiTable\AgenciesModel;
use App\Models\MultiTable\AgencyCodesModel;
use App\Models\MultiTable\AgentStatusModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\ContractTypeModel;
use App\Models\MultiTable\PolicyStatusModel;
use App\Models\MultiTable\SalesRegionModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesRegionModel::create(["name" => "RF - North Florida", "description" => "RF - North Florida", "sort_order" => "-1", "status" => "1"]);
        SalesRegionModel::create(["name" => "ROF-SQS", "description" => "RF - North Florida", "sort_order" => "1", "status" => "1"]);
        SalesRegionModel::create(["name" => "SF - South Florida", "description" => "SF - South Florida", "sort_order" => "2", "status" => "1"]);
        SalesRegionModel::create(["name" => "TX - Texas", "description" => "TX - Texas", "sort_order" => "3", "status" => "1"]);

        ContractTypeModel::create(["name" => "Agent 1", "description" => "Agent 1", "sort_order" => "-1", "status" => "1"]);
        ContractTypeModel::create(["name" => "Agent 2", "description" => "Agent 2", "sort_order" => "1", "status" => "1"]);
        ContractTypeModel::create(["name" => "Agent 3", "description" => "Agent 3", "sort_order" => "2", "status" => "1"]);
        ContractTypeModel::create(["name" => "Agent 4", "description" => "Agent 4", "sort_order" => "3", "status" => "1"]);

        AgencyCodesModel::create(["name" => "107 - TexasBlue", "description" => "107 - TexasBlue", "sort_order" => "-1", "status" => "1"]);
        AgencyCodesModel::create(["name" => "5071 - Tampa II", "description" => "5071 - Tampa II", "sort_order" => "-1", "status" => "1"]);

        CarriersModel::create(["name" => "Florida Blue", "description" => "Florida Blue", "sort_order" => "-1", "status" => "1"]);
        CarriersModel::create(["name" => "AARP", "description" => "AARP", "sort_order" => "-1", "status" => "1"]);

        AgentTitlesModel::create(["name" => "Agent", "description" => "Agent", "sort_order" => "-1", "status" => "1"]);
        AgentTitlesModel::create(["name" => "Agent Profile", "description" => "Agent Profile", "sort_order" => "-1", "status" => "1"]);

        AgentStatusModel::create(["name" => "Active", "description" => "Active", "sort_order" => "-1", "status" => "1"]);
        AgentStatusModel::create(["name" => "Deceased", "description" => "Deceased", "sort_order" => "-1", "status" => "1"]);

        AgenciesModel::create(["name" => "4 WORK CORP", "description" => "4 WORK CORP", "sort_order" => "-1", "status" => "1"]);
        AgenciesModel::create(["name" => "A AND T Global service and Investment LLC", "description" => "A AND T Global service and Investment LLC", "sort_order" => "-1", "status" => "1"]);

        AdminFeesModel::create(["name" => "AMF", "description" => "AMF", "sort_order" => "-1", "status" => "1"]);
        AdminFeesModel::create(["name" => "Surebridge Reserve", "description" => "Surebridge Reserve", "sort_order" => "-1", "status" => "1"]);

        PolicyStatusModel::create(["name" => "Active", "description" => "Active", "sort_order" => "-1", "status" => "1"]);
        PolicyStatusModel::create(["name" => "Canceled", "description" => "Canceled", "sort_order" => "-1", "status" => "1"]);
    }
}
