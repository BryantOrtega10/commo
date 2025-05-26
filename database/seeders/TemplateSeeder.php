<?php

namespace Database\Seeders;

use App\Models\Commissions\TemplatesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TemplatesModel::create(["name" => "Manual", "download_route" => "#"]);
        TemplatesModel::create(["name" => "EG1", "download_route" => "EG01.xlsx"]);
        TemplatesModel::create(["name" => "E13", "download_route" => "EI13.xlsx"]);
        TemplatesModel::create(["name" => "E14", "download_route" => "EI14.xlsx"]);
        TemplatesModel::create(["name" => "E16", "download_route" => "EI16.xlsx"]);

    }
}
