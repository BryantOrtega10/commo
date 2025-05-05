<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Models\Commissions\TemplatesModel;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function loadInfo($id = null)
    {
        if (empty($id)) {
            return response()->json([
                "success" => false,
                "message" => "Please send template ID"
            ], 400);
        }

        $template = TemplatesModel::where("id", "=", $id)->first();

        return response()->json([
            "success" => true,
            "download-route" => "/templates/$template->download_route"
        ]);
    }
}
