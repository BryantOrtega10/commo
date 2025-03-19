<?php

namespace App\Http\Controllers;

use App\Models\RelationshipsModel;
use Illuminate\Http\Request;

class RelationshipsController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            RelationshipsModel::class,  //model
            "layouts",  //baseView
            "relationships",  //baseRoute
            "Relationships",  //pluralTitle
            "Relationship"  //singularTitle
        );
    }
}