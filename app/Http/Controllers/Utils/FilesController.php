<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Utils\FilesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function uploadFile(Request $request)
    {
        if (!$request->hasFile("file")) {
            return response()->json([
                "success" => false,
                "error" => "The file was not attached"
            ], 401);
        }

        $file = $request->file("file");

        $subFolder = "";
        if ($request->has("customer_id")) {
            $subFolder = "customers";
        }
        if ($request->has("agent_id")) {
            $subFolder = "agents";
        }
        if ($request->has("agent_number_id")) {
            $subFolder = "agent_numbers";
        }

        if ($request->has("policy_id")) {
            $subFolder = "policies";
        }

        

        $file_name =  time() . "_" . $file->getClientOriginalName();
        $file->move(public_path("storage/" . $subFolder), $file_name);

        $fileDB = new FilesModel();
        $fileDB->name = $file->getClientOriginalName();
        $fileDB->route = $file_name;
        if ($request->has("customer_id")) {
            $fileDB->fk_customer = $request->input("customer_id");
        }
        if ($request->has("agent_id")) {
            $fileDB->fk_agent = $request->input("agent_id");
        }
        if ($request->has("agent_number_id")) {
            $fileDB->fk_agent_number = $request->input("agent_number_id");
        }
        if ($request->has("policy_id")) {
            $fileDB->fk_policy = $request->input("policy_id");
        }

        $fileDB->save();

        Utils::createLog(
            "The user has uploaded file with ID: ".$fileDB->id,
            "files",
            "create"
        );

        return response()->json([
            "success" => true,
            "filename" => $file->getClientOriginalName(),
            "route" => Storage::url($subFolder . '/' . $fileDB->route),
            "removeRoute" => route('files.delete',['id' => $fileDB->id])
        ]);
    }

    public function remove($id, Request $request){

        $fileDB = FilesModel::find($id);
        $folder = "";
        if(isset($fileDB->fk_customer)){
            $folder = "customers/";
        }
        if(isset($fileDB->fk_agent)){
            $folder = "agents/";
        }
        if(isset($fileDB->fk_agent)){
            $folder = "agent_numbers/";
        }
        if(isset($fileDB->fk_policy)){
            $folder = "policies/";
        }
        $fileFinal = $folder . $fileDB->route;
        
        Storage::disk('public')->delete($fileFinal);

        $fileDB->delete();

        Utils::createLog(
            "The user has deleted file with ID: ".$id,
            "files",
            "delete"
        );

        return redirect($request->server("HTTP_REFERER"))->with("message","File removed successfully!");

    }
}
