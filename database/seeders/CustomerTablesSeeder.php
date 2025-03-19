<?php

namespace Database\Seeders;

use App\Models\BusinessTypesModel;
use App\Models\CountiesModel;
use App\Models\CustomerStatusModel;
use App\Models\GendersModel;
use App\Models\LegalBasisModel;
use App\Models\MaritalStatusModel;
use App\Models\PhasesModel;
use App\Models\RegionsModel;
use App\Models\RegistrationSourcesModel;
use App\Models\StatesModel;
use App\Models\SuffixesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        BusinessTypesModel::create(["name" => "Individual", "description" => "Individual", "sort_order" => "1", "status" => "1"]);
        BusinessTypesModel::create(["name" => "Group", "description" => "Group", "sort_order" => "2", "status" => "1"]);
        
        GendersModel::create(["name" => "Unknown", "description" => "Unknown", "sort_order" => "-1", "status" => "1"]);
        GendersModel::create(["name" => "Male", "description" => "Male", "sort_order" => "1", "status" => "1"]);
        GendersModel::create(["name" => "Female", "description" => "Female", "sort_order" => "2", "status" => "1"]);
        
        MaritalStatusModel::create(["name" => "Unknown", "description" => "Unknown", "sort_order" => "-1", "status" => "1"]);
        MaritalStatusModel::create(["name" => "Single", "description" => "Single", "sort_order" => "0", "status" => "1"]);
        MaritalStatusModel::create(["name" => "Married", "description" => "Married", "sort_order" => "0", "status" => "1"]);
        MaritalStatusModel::create(["name" => "Widowed", "description" => "Widowed", "sort_order" => "0", "status" => "1"]);

        RegionsModel::create(["name" => "Test Region", "description" => "Test Region", "sort_order" => "-1", "status" => "1"]);
        RegionsModel::create(["name" => "Region Test", "description" => "Region Test", "sort_order" => "-1", "status" => "1"]);

        $stateDefault = StatesModel::create(["name" => "Unknown", "description" => "Unknown", "sort_order" => "-1", "status" => "1"]);
        $state1 = StatesModel::create(["name" => "California", "description" => "California", "sort_order" => "1", "status" => "1"]);
        $state2 = StatesModel::create(["name" => "Texas", "description" => "Texas", "sort_order" => "2", "status" => "1"]);
        
        $regionDefault = RegionsModel::create(["name" => "Unknown", "description" => "Unknown", "sort_order" => "-1", "status" => "1"]);
        $region1 = RegionsModel::create(["name" => "TX", "description" => "TX", "sort_order" => "1", "status" => "1"]);
        $region2 = RegionsModel::create(["name" => "CA", "description" => "CA", "sort_order" => "2", "status" => "1"]);

        CountiesModel::create(["name" => "Unknown", "description" => "Unknown", "sort_order" => "-1", "status" => "1", "fk_state" => $stateDefault->id, "fk_region" => $regionDefault->id]);
        CountiesModel::create(["name" => "County TX", "description" => "CA", "sort_order" => "1", "status" => "1", "fk_state" => $state1->id, "fk_region" => $region1->id]);
        CountiesModel::create(["name" => "County CA", "description" => "CA", "sort_order" => "2", "status" => "1", "fk_state" => $state2->id, "fk_region" => $region2->id]);
                

        SuffixesModel::create(["name" => "Test Suffix", "description" => "Test Suffix", "sort_order" => "-1", "status" => "1"]);
        SuffixesModel::create(["name" => "Suffix Test", "description" => "Suffix Test", "sort_order" => "-1", "status" => "1"]);
        
        CustomerStatusModel::create(["name" => "New Lead", "description" => "New Lead", "sort_order" => "-1", "status" => "1"]);
        CustomerStatusModel::create(["name" => "Lead", "description" => "Lead", "sort_order" => "-1", "status" => "1"]);
        CustomerStatusModel::create(["name" => "Customer", "description" => "Customer", "sort_order" => "-1", "status" => "1"]);
        
        PhasesModel::create(["name" => "Acquisition", "description" => "Acquisition", "sort_order" => "-1", "status" => "1"]);
        PhasesModel::create(["name" => "Conversion", "description" => "Conversion", "sort_order" => "-1", "status" => "1"]);
        PhasesModel::create(["name" => "Retention", "description" => "Retention", "sort_order" => "-1", "status" => "1"]);
        
        LegalBasisModel::create(["name" => "Legitimate interest", "description" => "Legitimate interest", "sort_order" => "-1", "status" => "1"]);
        LegalBasisModel::create(["name" => "Legal Basis Test", "description" => "Legal Basis Test", "sort_order" => "-1", "status" => "1"]);
        
        RegistrationSourcesModel::create(["name" => "Ads Google", "description" => "Ads Google", "sort_order" => "-1", "status" => "1"]);
        RegistrationSourcesModel::create(["name" => "Friends", "description" => "Friends", "sort_order" => "-1", "status" => "1"]);
        RegistrationSourcesModel::create(["name" => "Marketing campaign", "description" => "Marketing campaign", "sort_order" => "-1", "status" => "1"]);
        
    }
}
