<?php

namespace App\Http\Controllers;

use App\Models\GendersModel;
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