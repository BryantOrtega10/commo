<?php

namespace App\Http\Controllers;

use App\Models\ClientSourcesModel;
use Illuminate\Http\Request;

class ClientSourcesController extends Controller
{
    private $MODEL;

    public function __construct() {
        $this->MODEL = ClientSourcesModel::class;
    }

    public function show(){
        $items = $this->MODEL::get();
        return view('policies.client-sources.table',[
            'items' => $items
        ]);
    }
}
