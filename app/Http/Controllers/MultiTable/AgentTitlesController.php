<?php

namespace App\Http\Controllers\MultiTable;

use App\Models\MultiTable\AgentTitlesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\MultipleTableController;

class AgentTitlesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            AgentTitlesModel::class,  //model
            "layouts",  //baseView
            "agent-titles",  //baseRoute
            "Agent Titles",  //pluralTitle
            "Agent Title"  //singularTitle
        );
    }
}