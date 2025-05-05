<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Commissions\ImportCommissionsRequest;
use App\Imports\CommissionRowImport;
use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Commissions\CommissionUploadsModel;
use App\Models\Commissions\TemplatesModel;
use App\Models\MultiTable\CarriersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CommissionsController extends Controller
{
    public function show()
    {

        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $templates = TemplatesModel::where("id", ">", "1")->get();

        return view('commissions.calculation', [
            "carriers" => $carriers,
            "templates" => $templates,
        ]);
    }

    public function import(ImportCommissionsRequest $request)
    {

        $entry_user = Auth::user();

        $file = $request->file("file-excel");

        $fileName =  time() . "_" . $file->getClientOriginalName();
        // $file->move(public_path("storage/commissions"), $fileName);
        Storage::disk('public')->putFileAs('commissions', $file, $fileName);

        $commissionUpload = new CommissionUploadsModel();
        $commissionUpload->name = $file->getClientOriginalName();
        $commissionUpload->file_route = Storage::url('commissions/' . $fileName);
        $commissionUpload->statement_date = $request->input("statement_date");
        $commissionUpload->check_date = $request->input("check_date");
        $commissionUpload->fk_template = $request->input("template");
        $commissionUpload->fk_carrier = $request->input("carrier");
        $commissionUpload->fk_entry_user = $entry_user->id;
        $commissionUpload->save();
        $filePath = Storage::disk('public')->path('commissions/' . $fileName);

        //dump($filePath);

        $commissionUploadId = $commissionUpload->id;

        Excel::queueImport(new CommissionRowImport($commissionUploadId), $filePath);

        return redirect(route('commissions.calculation.showImport', ['id' => $commissionUpload->id]))->with('message', 'The commission file is uploading');
    }

    public function showImport($id)
    {
        $commissionUpload = CommissionUploadsModel::find($id);
        if ($commissionUpload->status == 0) {
            return view('commissions.uploading', [
                "commissionUpload" => $commissionUpload
            ]);
        }
        $firstCommission = CommissionUploadRowsModel::where("fk_commission_upload", "=", $id)->first();
        $data = json_decode($firstCommission->data, true);
        $headersSnake = array_keys($data);
        $headersTitle = array_map(function ($field) {
            return ucwords(str_replace('_', ' ', $field));
        }, $headersSnake);


        return view('commissions.showImport', [
            "commissionUpload" => $commissionUpload,
            "headersSnake" => $headersSnake,
            "headersTitle" => $headersTitle
        ]);
    }

    public function loadRowsUploaded($id)
    {
        $commissionUpload = CommissionUploadsModel::find($id);

        return response()->json([
            "rows_uploaded" => $commissionUpload->rows_uploaded,
            "status" => $commissionUpload->status,
        ]);
    }

    public function datatableAjax($id, Request $request)
    {

        $commissionRows = CommissionUploadRowsModel::select(
            "commission_upload_rows.*"
        )->where("fk_commission_upload", "=", $id);

        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $commissionRows->where(function ($query) use ($searchTxt) {
                $query->where("data", "like", "%{$searchTxt}%")
                    ->orWhere("notes", "like", "%{$searchTxt}%")
                    ->orWhereRaw("
                        CASE status
                            WHEN 0 THEN 'Unlinked'
                            WHEN 1 THEN 'Linking'
                            WHEN 2 THEN 'Linked'
                        END LIKE ?
                    ", [$searchTxt]);
            });
        }

        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $commissionRows->orderBy("id", $direction);
                    break;
                case '1':
                    $commissionRows->orderByRaw("CASE status
                            WHEN 0 THEN 'Unlinked'
                            WHEN 1 THEN 'Linking'
                            WHEN 2 THEN 'Linked'
                        END $direction");
                    break;
                case '2':
                    $commissionRows->orderBy("notes", $direction);
                    break;
            }
        }




        $totalRecords = $commissionRows->count();
        $commissionRows = $commissionRows->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($commissionRows as $commissionRow) {

            $filteredRecord = array();
            $filteredRecord["id"]["id"] = $commissionRow->id;
            $filteredRecord["status"] = $commissionRow->txt_status;
            $filteredRecord["notes"] = $commissionRow->notes ?? "";
            
            $data = json_decode($commissionRow->data, true);
            foreach ($data as $index => $item) {
                $filteredRecord[$index] = Utils::rowFormat($index, $item);
            }
            array_push($filteredRecords, $filteredRecord);
        }


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);
    }

    

}
