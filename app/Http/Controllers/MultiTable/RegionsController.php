<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\RegionsModel;
use Illuminate\Http\Request;

class RegionsController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            RegionsModel::class,  //model
            "layouts",  //baseView
            "regions",  //baseRoute
            "Regions",  //pluralTitle
            "Region"  //singularTitle
        );
    }
}