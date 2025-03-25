<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\ContractTypeModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

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