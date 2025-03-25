<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\AgentStatusModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class AgentStatusController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            AgentStatusModel::class,  //model
            "layouts",  //baseView
            "agent-status",  //baseRoute
            "Agent Status",  //pluralTitle
            "Agent Status"  //singularTitle
        );
    }
}