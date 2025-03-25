<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\PlanTypesModel;
use Illuminate\Http\Request;

class PlanTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            PlanTypesModel::class,  //model
            "layouts",  //baseView
            "plan-types",  //baseRoute
            "Plan Types",  //pluralTitle
            "Plan Type"  //singularTitle
        );
    }
}