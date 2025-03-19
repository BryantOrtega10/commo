<?php

namespace App\Http\Controllers;

use App\Models\BusinessSegmentsModel;
use Illuminate\Http\Request;

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