<?php

namespace App\Http\Controllers;

use App\Models\MemberTypesModel;
use Illuminate\Http\Request;

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