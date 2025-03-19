<?php

namespace App\Http\Controllers;

use App\Models\StatesModel;
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