<?php

namespace App\Http\Controllers;

use App\Models\MaritalStatusModel;
use Illuminate\Http\Request;

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