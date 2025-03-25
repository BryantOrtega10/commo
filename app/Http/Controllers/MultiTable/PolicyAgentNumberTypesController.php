<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\PolicyAgentNumberTypesModel;
use Illuminate\Http\Request;

class PolicyAgentNumberTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            PolicyAgentNumberTypesModel::class,  //model
            "layouts",  //baseView
            "policy-agent-number-types",  //baseRoute
            "Policy Agent Number Types",  //pluralTitle
            "Policy Agent Number Type"  //singularTitle
        );
    }
}