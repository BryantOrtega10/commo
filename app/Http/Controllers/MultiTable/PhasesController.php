<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\PhasesModel;
use Illuminate\Http\Request;

class PhasesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            PhasesModel::class,  //model
            "layouts",  //baseView
            "phases",  //baseRoute
            "Phase",  //pluralTitle
            "Phases"  //singularTitle
        );
    }
}