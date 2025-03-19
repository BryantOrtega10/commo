<?php

namespace App\Http\Controllers;

use App\Models\LegalBasisModel;
use Illuminate\Http\Request;

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