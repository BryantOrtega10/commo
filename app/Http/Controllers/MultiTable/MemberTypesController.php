<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\MemberTypesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class MemberTypesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            MemberTypesModel::class,  //model
            "layouts",  //baseView
            "policy-member-types",  //baseRoute
            "Policy Member Types",  //pluralTitle
            "Policy Member Type"  //singularTitle
        );
    }
}