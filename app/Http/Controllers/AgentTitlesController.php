<?php

namespace App\Http\Controllers;

use App\Models\AgentTitlesModel;
use Illuminate\Http\Request;

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