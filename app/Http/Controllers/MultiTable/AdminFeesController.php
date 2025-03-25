<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\AdminFeesModel;
use Illuminate\Http\Request;

class AdminFeesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            AdminFeesModel::class,  //model
            "layouts",  //baseView
            "admin-fees",  //baseRoute
            "Admin Fees",  //pluralTitle
            "Admin Fee"  //singularTitle
        );
    }
}