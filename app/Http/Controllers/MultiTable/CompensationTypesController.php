<?php

namespace App\Http\Controllers\MultiTable;

use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;
use App\Models\Commissions\CompensationTypesModel;

class CompensationTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            CompensationTypesModel::class,  //model
            "layouts",  //baseView
            "compensation-types",  //baseRoute
            "Compensation Types",  //pluralTitle
            "Compensation Type"  //singularTitle
        );
    }
}