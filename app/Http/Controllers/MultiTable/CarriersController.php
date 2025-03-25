<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\CarriersModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class CarriersController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            CarriersModel::class,  //model
            "layouts",  //baseView
            "carriers",  //baseRoute
            "Carriers",  //pluralTitle
            "Carrier"  //singularTitle
        );
    }
}