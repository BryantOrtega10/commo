<?php

namespace App\Http\Controllers\MultiTable;

use App\Http\Controllers\MultipleTableController;
use App\Models\MultiTable\PolicyStatusModel;
use Illuminate\Http\Request;

class PolicyStatusController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            PolicyStatusModel::class,  //model
            "layouts",  //baseView
            "policy-status",  //baseRoute
            "Policy Statuses",  //pluralTitle
            "Policy Status"  //singularTitle
        );
    }
}