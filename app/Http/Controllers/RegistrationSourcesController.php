<?php

namespace App\Http\Controllers;

use App\Models\RegistrationSourcesModel;
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