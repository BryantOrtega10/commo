<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\EnrollmentMethodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class EnrollmentMethodsController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            EnrollmentMethodsModel::class,  //model
            "layouts",  //baseView
            "enrollment-methods",  //baseRoute
            "Enrollment Methods",  //pluralTitle
            "Enrollment Method"  //singularTitle
        );
    }
}