<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultipleTableRequest;
use App\Models\ClientSourcesModel;
use Exception;
use Illuminate\Http\Request;

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
