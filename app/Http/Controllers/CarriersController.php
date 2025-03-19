<?php

namespace App\Http\Controllers;

use App\Models\CarriersModel;
use Illuminate\Http\Request;

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