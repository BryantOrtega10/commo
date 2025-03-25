<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\MaritalStatusModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class MaritalStatusController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            MaritalStatusModel::class,  //model
            "layouts",  //baseView
            "marital-status",  //baseRoute
            "Marital Statuses",  //pluralTitle
            "Marital Status"  //singularTitle
        );
    }
}