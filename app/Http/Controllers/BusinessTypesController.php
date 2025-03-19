<?php

namespace App\Http\Controllers;

use App\Models\BusinessTypesModel;
use Illuminate\Http\Request;

class BusinessTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            BusinessTypesModel::class,  //model
            "layouts",  //baseView
            "business-types",  //baseRoute
            "Business Types",  //pluralTitle
            "Business Type"  //singularTitle
        );
    }
}