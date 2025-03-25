<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\RegistrationSourcesModel;
use Illuminate\Http\Request;

class RegistrationSourcesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            RegistrationSourcesModel::class,  //model
            "layouts",  //baseView
            "registration-sources",  //baseRoute
            "Registration Sources",  //pluralTitle
            "Registration Source"  //singularTitle
        );
    }
}