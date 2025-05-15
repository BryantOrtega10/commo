<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Commissions\ImportCommissionsRequest;
use App\Imports\CommissionRowImport;
use App\Jobs\LinkCommissionUploadsJob;
use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Commissions\CommissionUploadsModel;
use App\Models\Commissions\TemplatesModel;
use App\Models\MultiTable\CarriersModel;
use App\Services\Commissions\CommissionRowProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CommissionsController extends Controller
{
    public function show()
    {

        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $templates = TemplatesModel::where("id", ">", "1")->get();
        $commissionUploads = CommissionUploadsModel::orderBy("created_at", "DESC")->get();
        return view('commissions.calculation', [
            "carriers" => $carriers,
            "templates" => $templates,
            "commissionUploads" => $commissionUploads
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

        $percentageLinked = round($commissionUpload->processed_rows * 100 / $commissionUpload->uploaded_rows, 2);
        $percentageError = round($commissionUpload->error_rows * 100 / $commissionUpload->uploaded_rows, 2);
        $percentageUploaded = 100 - $percentageLinked - $percentageError;

        return view('commissions.showImport', [
            "commissionUpload" => $commissionUpload,
            "headersSnake" => $headersSnake,
            "headersTitle" => $headersTitle,
            "percentageLinked" => $percentageLinked,
            "percentageError" => $percentageError,
            "percentageUploaded" => $percentageUploaded,
        ]);
    }

    public function loadUploadedRows($id)
    {
        $commissionUpload = CommissionUploadsModel::find($id);

        $percentageLinked = round($commissionUpload->processed_rows * 100 / $commissionUpload->uploaded_rows, 2);
        $percentageError = round($commissionUpload->error_rows * 100 / $commissionUpload->uploaded_rows, 2);
        $percentageUploaded = 100 - $percentageLinked - $percentageError;

        return response()->json([
            "uploaded_rows" => $commissionUpload->uploaded_rows,
            "processed_rows" => $commissionUpload->processed_rows,
            "error_rows" => $commissionUpload->error_rows,
            "status" => $commissionUpload->status,
            "percentage_uploaded" => $percentageUploaded,
            "percentage_linked" => $percentageLinked,
            "percentage_error" => $percentageError,
        ]);
    }

    public function datatableAjax($id, Request $request)
    {
        $firstCommission = CommissionUploadRowsModel::where("fk_commission_upload", "=", $id)->first();
        $data = json_decode($firstCommission->data, true);
        $headersSnake = array_keys($data);

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
            if ($column >= 3) {
                $commissionRows->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$." . $headersSnake[$column - 3] . "'))" . " " . $direction);
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

    public function linkAllCommissions($id)
    {

        $commissionUpload = CommissionUploadsModel::find($id);
        $commissionUpload->status = 2;
        $commissionUpload->save();

        $commissionRows = CommissionUploadRowsModel::select("commission_upload_rows.*")
            ->where("fk_commission_upload", "=", $id)
            ->where("status", "=", 0)
            ->get()
            ->toArray();

        $jobs = array_map(fn($commissionRow) => new LinkCommissionUploadsJob($commissionRow['id']), $commissionRows);

        Bus::batch($jobs)
            ->finally(function ($batch) use ($id) {
                $commissionUpload = CommissionUploadsModel::find($id);
                $commissionRowsUpdated = CommissionUploadRowsModel::select("commission_upload_rows.*")
                    ->where("fk_commission_upload", "=", $id)
                    ->where("status", "=", 1)
                    ->update(["status" => 3]);
                $commissionUpload->error_rows = $commissionUpload->error_rows + $commissionRowsUpdated;

                if ($commissionUpload->error_rows > 0) {
                    $commissionUpload->status = 3;
                } else {
                    $commissionUpload->status = 4;
                }
                $commissionUpload->save();
            })
            ->dispatch();

        return redirect(route('commissions.calculation.showImport', ['id' => $id]))->with('message', 'Commision upladoad is linking');
    }

    public function linkErrors($id)
    {

        $commissionUpload = CommissionUploadsModel::find($id);
        $commissionUpload->status = 2;
        $commissionUpload->error_rows = 0;
        $commissionUpload->save();

        $commissionRows = CommissionUploadRowsModel::select("commission_upload_rows.*")
            ->where("fk_commission_upload", "=", $id)
            ->where("status", "=", 3)
            ->get()
            ->toArray();


        $jobs = array_map(fn($commissionRow) => new LinkCommissionUploadsJob($commissionRow['id']), $commissionRows);

        Bus::batch($jobs)
            ->then(function ($batch) use ($id) {
                Log::info("Todas las filas fueron procesadas");
            })
            ->catch(fn($batch, $e) => Log::error("Error al procesar: " . $e->getMessage()))
            ->finally(function ($batch) use ($id) {
                $commissionUpload = CommissionUploadsModel::find($id);
                $commissionRowsUpdated = CommissionUploadRowsModel::select("commission_upload_rows.*")
                    ->where("fk_commission_upload", "=", $id)
                    ->where("status", "=", 1)
                    ->update(["status" => 3]);
                $commissionUpload->error_rows = $commissionUpload->error_rows + $commissionRowsUpdated;

                if ($commissionUpload->error_rows > 0) {
                    $commissionUpload->status = 3;
                } else {
                    $commissionUpload->status = 4;
                }
                $commissionUpload->save();
            })
            ->dispatch();

        return redirect(route('commissions.calculation.showImport', ['id' => $id]))->with('message', 'Commision upladoad is linking');
    }

    public function testRow($id)
    {


        // $procesor = new CommissionRowProcessor();
        // $procesor->startProcess($id);
    }
}
