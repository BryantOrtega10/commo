<?php

namespace Database\Seeders;

use App\Models\Utils\EmailTemplateModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplateModel::create(["description" => "<p>Hello, this is your statement for __statement_date__</p>"]);
    }
}
