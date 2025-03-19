<?php

namespace App\Http\Controllers;

use App\Models\CustomerStatusModel;
use Illuminate\Http\Request;

class CustomerStatusController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            CustomerStatusModel::class,  //model
            "layouts",  //baseView
            "customer-status",  //baseRoute
            "Customer Status",  //pluralTitle
            "Customer Status"  //singularTitle
        );
    }
}