<?php

namespace App\Http\Controllers;

use App\Models\ContractTypeModel;
use Illuminate\Http\Request;

class ContractTypeController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            ContractTypeModel::class,  //model
            "layouts",  //baseView
            "contract-types",  //baseRoute
            "Contract Types",  //pluralTitle
            "Contract Type"  //singularTitle
        );
    }
}