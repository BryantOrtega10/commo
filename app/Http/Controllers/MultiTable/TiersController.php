<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\TiersModel;
use Illuminate\Http\Request;

class TiersController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            TiersModel::class,  //model
            "layouts",  //baseView
            "tiers",  //baseRoute
            "Tiers",  //pluralTitle
            "Tier"  //singularTitle
        );
    }
}