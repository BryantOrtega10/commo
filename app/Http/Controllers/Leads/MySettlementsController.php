<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MySettlementsController extends Controller
{
    public function show(Request $request){
        $settlements = [];
        
        return view('leads.mySettlements',[
            'settlements' => $settlements
        ]);
    }
}
