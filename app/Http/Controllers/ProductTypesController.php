<?php

namespace App\Http\Controllers;

use App\Models\ProductTypesModel;
use Illuminate\Http\Request;

class ProductTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            ProductTypesModel::class,  //model
            "layouts",  //baseView
            "types",  //baseRoute
            "Types",  //pluralTitle
            "Type"  //singularTitle
        );
    }
}