<?php

namespace App\Http\Controllers;

use App\Models\AgenciesModel;
use Illuminate\Http\Request;

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