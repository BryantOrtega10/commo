<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllSalesController extends Controller
{
    public function showAllSalesForm(){
        return view('commissions.showAllSalesReport');
    }

    public function generateAllSalesReport(){
        //
    }
}
