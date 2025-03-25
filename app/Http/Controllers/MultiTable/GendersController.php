<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\GendersModel;
use Illuminate\Http\Request;

class GendersController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            GendersModel::class,  //model
            "layouts",  //baseView
            "genders",  //baseRoute
            "Genders",  //pluralTitle
            "Gender"  //singularTitle
        );
    }
}