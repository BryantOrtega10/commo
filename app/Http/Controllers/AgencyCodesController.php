<?php

namespace App\Http\Controllers;

use App\Models\AgencyCodesModel;
use Illuminate\Http\Request;

class AgencyCodesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            AgencyCodesModel::class,  //model
            "layouts",  //baseView
            "agency-codes",  //baseRoute
            "Agency Codes",  //pluralTitle
            "Agency Code"  //singularTitle
        );
    }
}