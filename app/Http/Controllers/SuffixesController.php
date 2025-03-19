<?php

namespace App\Http\Controllers;

use App\Models\SuffixesModel;
use Illuminate\Http\Request;

class SuffixesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            SuffixesModel::class,  //model
            "layouts",  //baseView
            "suffixes",  //baseRoute
            "Suffixes",  //pluralTitle
            "Suffix"  //singularTitle
        );
    }
}