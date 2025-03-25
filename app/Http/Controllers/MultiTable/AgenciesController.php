<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\AgenciesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class AgenciesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            AgenciesModel::class,  //model
            "layouts",  //baseView
            "agencies",  //baseRoute
            "Agencies",  //pluralTitle
            "Agency"  //singularTitle
        );
    }
}