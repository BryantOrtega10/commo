<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentMethodsModel;
use Illuminate\Http\Request;

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