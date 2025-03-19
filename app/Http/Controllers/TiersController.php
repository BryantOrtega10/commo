<?php

namespace App\Http\Controllers;

use App\Models\TiersModel;
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