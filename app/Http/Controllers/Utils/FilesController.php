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

        $file_name =  time() . "_" . $file->getClientOriginalName();
        $file->move(public_path("storage/" . $subFolder), $file_name);

        $fileDB = new FilesModel();
        $fileDB->name = $file->getClientOriginalName();
        $fileDB->route = $file_name;
        if ($request->has("customer_id")) {
            $fileDB->fk_customer = $request->input("customer_id");
        }
        $fileDB->save();

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
        $fileFinal = $folder . $fileDB->route;
        
        Storage::disk('public')->delete($fileFinal);

        $fileDB->delete();

        return redirect($request->server("HTTP_REFERER"))->with("message","File removed successfully!");

    }
}
