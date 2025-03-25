<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\AgencyCodesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

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