<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\ClientSourcesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class ClientSourcesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            ClientSourcesModel::class,  //model
            "layouts",  //baseView
            "client-sources",  //baseRoute
            "Client Sources",  //pluralTitle
            "Client Source"  //singularTitle
        );
    }
}
