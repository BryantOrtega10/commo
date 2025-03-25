<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\CustomerStatusModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

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