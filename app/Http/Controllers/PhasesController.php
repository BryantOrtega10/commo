<?php

namespace App\Http\Controllers;

use App\Models\PhasesModel;
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