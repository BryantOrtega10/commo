<?php

namespace Database\Seeders;

use App\Models\Commissions\CompensationTypesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompensationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        CompensationTypesModel::create(["name" => "ACCT MGT FEE", "description" => "ACCT MGT FEE", "sort_order" => "0", "status" => "1"]);
        CompensationTypesModel::create(["name" => "NEW SALE", "description" => "NEW SALE", "sort_order" => "1", "status" => "1"]);
        CompensationTypesModel::create(["name" => "RENEWAL", "description" => "RENEWAL", "sort_order" => "2", "status" => "1"]);
        CompensationTypesModel::create(["name" => "SEP NEWSALE BONUS", "description" => "SEP NEWSALE BONUS", "sort_order" => "3", "status" => "1"]);
        CompensationTypesModel::create(["name" => "Prior Balance", "description" => "Prior Balance", "sort_order" => "4", "status" => "1"]);
        CompensationTypesModel::create(["name" => "Adjustment", "description" => "Adjustment", "sort_order" => "5", "status" => "1"]);

    }
}
