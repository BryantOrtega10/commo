<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\BusinessTypesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

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