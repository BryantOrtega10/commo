<?php

namespace App\Http\Controllers;

use App\Models\PolicyAgentNumberTypesModel;
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