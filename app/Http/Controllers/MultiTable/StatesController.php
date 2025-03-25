<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\StatesModel;
use Illuminate\Http\Request;

class StatesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            StatesModel::class,  //model
            "layouts",  //baseView
            "states",  //baseRoute
            "States",  //pluralTitle
            "State"  //singularTitle
        );
    }
}