<?php

namespace App\Http\Controllers;

use App\Models\SalesRegionModel;
use Illuminate\Http\Request;

class SalesRegionController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            SalesRegionModel::class,  //model
            "layouts",  //baseView
            "sales-regions",  //baseRoute
            "Sales Regions",  //pluralTitle
            "Sales Region"  //singularTitle
        );
    }
}