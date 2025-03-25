<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\BusinessSegmentsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class BusinessSegmentsController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            BusinessSegmentsModel::class,  //model
            "layouts",  //baseView
            "business-segments",  //baseRoute
            "Business Segments",  //pluralTitle
            "Business Segment"  //singularTitle
        );
    }
}