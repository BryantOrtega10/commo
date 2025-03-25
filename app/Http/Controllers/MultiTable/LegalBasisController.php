<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\LegalBasisModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class LegalBasisController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            LegalBasisModel::class,  //model
            "layouts",  //baseView
            "legal-basis",  //baseRoute
            "Legal Basis",  //pluralTitle
            "Legal Basis"  //singularTitle
        );
    }
}