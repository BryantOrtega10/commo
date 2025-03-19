<?php

namespace App\Http\Controllers;

use App\Models\CountiesModel;
use Illuminate\Http\Request;

class CountiesController extends MultipleTableController
{
    public function __construct()
    {
        parent::__construct(
            CountiesModel::class,  //model
            "layouts",  //baseView
            "client-sources",  //baseRoute
            "Client Sources",  //pluralTitle
            "Client Source"  //singularTitle
        );
    }

    public function loadInfo($id = null){
        if(empty($id)){
            return response()->json([
                "success" => false,
                "message" => "Please send county ID"
            ],400);
        }

        $county = CountiesModel::where("id","=",$id)->with("state")->with("region")->first();

        return response()->json([
            "success" => true,
            "county" => $county
        ]);
    }
}