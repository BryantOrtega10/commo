<?php

namespace App\Http\Controllers;

use App\Models\AgentStatusModel;
use Illuminate\Http\Request;

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