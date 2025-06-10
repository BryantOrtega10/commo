<?php

namespace App\Http\Controllers\Commissions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Commissions\ImportCommissionsRequest;
use App\Imports\CommissionRowImport;
use App\Jobs\LinkCommissionUploadsJob;
use App\Models\Agents\AgentsModel;
use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Commissions\CommissionUploadsModel;
use App\Models\Commissions\StatementsItemModel;
use App\Models\Commissions\TemplatesModel;
use App\Models\MultiTable\AgentTitlesModel;
use App\Models\MultiTable\CarriersModel;
use App\Services\Commissions\CommissionRowProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
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
        
        Utils::createLog(
            "The user entered the commission calculation list.",
            "commissions.calculation",
            "show"
        );

        return view('commissions.calculation', [
            "carriers" => $carriers,
            "templates" => $templates,
            "commissionUploads" => $commissionUploads
        ]);
    }

    public function infoTemplate($id)
    {
        $template = TemplatesModel::find($id);

        return response()->json([
            "downloadUrl" => $template->download_route,
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

        $commissionUploadId = $commissionUpload->id;

        Excel::queueImport(new CommissionRowImport($commissionUploadId), $filePath);

        Utils::createLog(
            "The user has created a new commission upload with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
            "commissions.calculation",
            "create"
        );
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

        Utils::createLog(
            "The user has entered commission upload details with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
            "commissions.calculation",
            "show"
        );

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

            $count = 0;
            if (isset($commissionRow->transactions) && isset($commissionRow->transactions->statements)) {
                $count = sizeof($commissionRow->transactions->statements);
            }

            $filteredRecord["statements"]["href"] = route('commissions.calculation.showStatements', ['id' => $commissionRow->id]);
            $filteredRecord["statements"]["text"] = $count;

            $filteredRecord["actions"]["edit_href"] = route('commissions.calculation.update', ['id' => $commissionRow->id]);
            $filteredRecord["actions"]["delete_href"] = route('commissions.calculation.delete', ['id' => $commissionRow->id]);

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
        $entry_user = Auth::user();
        $commissionRows = CommissionUploadRowsModel::select("commission_upload_rows.*")
            ->where("fk_commission_upload", "=", $id)
            ->where("status", "=", 0)
            ->get()
            ->toArray();
        if (sizeof($commissionRows) > 0) {

            $commissionUpload = CommissionUploadsModel::find($id);
            $commissionUpload->status = 2;
            $commissionUpload->processing_start_date = date("Y-m-d H:i:s");
            $commissionUpload->save();

            $jobs = array_map(fn($commissionRow) => new LinkCommissionUploadsJob($commissionRow['id']), $commissionRows);

            Bus::batch($jobs)
                ->finally(function ($batch) use ($id, $entry_user) {
                    $commissionUpload = CommissionUploadsModel::find($id);
                    $commissionRowsUpdated = CommissionUploadRowsModel::select("commission_upload_rows.*")
                        ->where("fk_commission_upload", "=", $id)
                        ->where("status", "=", 1)
                        ->update(["status" => 3]);
                    $commissionUpload->error_rows = $commissionUpload->error_rows + $commissionRowsUpdated;
                    $commissionUpload->processing_end_date = date("Y-m-d H:i:s");
                    if ($commissionUpload->error_rows > 0) {
                        $commissionUpload->status = 3;
                    } else {
                        $commissionUpload->status = 4;
                    }
                    $commissionUpload->save();
                    Utils::createLog(
                        "The user has completed the commission link with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
                        "commissions.calculation",
                        "create",
                        $entry_user
                    );
                })
                ->dispatch();
        }

        Utils::createLog(
            "The user has initiated the commission link with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
            "commissions.calculation",
            "create"
        );

        return redirect(route('commissions.calculation.showImport', ['id' => $id]))->with('message', 'Commision upladoad is linking');
    }

    public function linkCommissions(Request $request)
    {
        $entry_user = Auth::user();

        $commissionRowsDB = CommissionUploadRowsModel::select("commission_upload_rows.*")
            ->whereIn("id", $request->input("commissionRow"));

        $commissionRows = $commissionRowsDB->get()->toArray();

        if (sizeof($commissionRows) > 0) {
            $commissionRowsLinked = $commissionRowsDB->where("status", "=", 2)->count();
            $commissionRowsError = $commissionRowsDB->where("status", "=", 3)->count();


            $commissionUpload = CommissionUploadsModel::find($commissionRows[0]['fk_commission_upload']);
            $commissionUpload->status = 2;
            $commissionUpload->processing_start_date = date("Y-m-d H:i:s");
            $commissionUpload->error_rows = $commissionUpload->error_rows - $commissionRowsError;
            $commissionUpload->processed_rows = $commissionUpload->processed_rows - $commissionRowsLinked;
            $commissionUpload->save();

            $jobs = array_map(fn($commissionRow) => new LinkCommissionUploadsJob($commissionRow['id']), $commissionRows);
            $ids = $request->input("commissionRow");
            $id = $commissionRows[0]['fk_commission_upload'];

            Bus::batch($jobs)
                ->finally(function ($batch) use ($ids, $id, $entry_user) {
                    $commissionRowsUpdated = CommissionUploadRowsModel::select("commission_upload_rows.*")
                        ->whereIn("id", $ids)
                        ->where("status", "=", 1)
                        ->update(["status" => 3]);

                    $commissionUpload = CommissionUploadsModel::find($id);
                    $commissionUpload->error_rows = $commissionUpload->error_rows + $commissionRowsUpdated;
                    $commissionUpload->processing_end_date = date("Y-m-d H:i:s");
                    if ($commissionUpload->error_rows > 0) {
                        $commissionUpload->status = 3;
                    } else {
                        $commissionUpload->status = 4;
                    }
                    $commissionUpload->save();
                    Utils::createLog(
                        "The user has completed the commission link with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
                        "commissions.calculation",
                        "create",
                        $entry_user
                    );
                })
                ->dispatch();
        }
        Utils::createLog(
            "The user has initiated the commission link with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
            "commissions.calculation",
            "create"
        );

        return redirect(route('commissions.calculation.showImport', ['id' => $id]))->with('message', 'Commision upladoad is linking');
    }

    public function linkErrors($id)
    {
        $entry_user = Auth::user();

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
            ->finally(function ($batch) use ($id, $entry_user) {
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
                Utils::createLog(
                    "The user has completed the commission link of errors with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
                    "commissions.calculation",
                    "create",
                    $entry_user
                );
            })
            ->dispatch();

        Utils::createLog(
            "The user has initiated the commission link of errors with ID: " . $commissionUpload->id." and file name: ".$commissionUpload->name,
            "commissions.calculation",
            "create"
        );

        return redirect(route('commissions.calculation.showImport', ['id' => $id]))->with('message', 'Commision upladoad is linking');
    }

    public function showModalStatements($id)
    {
        $statementItems = StatementsItemModel::select("statement_items.*")
            ->join("commission_transactions", "commission_transactions.id", "=", "statement_items.fk_commission_transaction")
            ->where("commission_transactions.fk_comm_upload_row", "=", $id)
            ->get();

        Utils::createLog(
            "The user has entered the statement modal to commission row: ".$id,
            "commissions.calculation",
            "show"
        );

        return view('commissions.partials.showStatementsModal', [
            "statementItems" => $statementItems
        ]);
    }

    public function showUpdateRow($id)
    {
        $commissionRow = CommissionUploadRowsModel::find($id);
        $data = json_decode($commissionRow->data, true);
        $formattedData = [];
        foreach ($data as $index => $item) {
            $formattedData[$index] = Utils::dbFormat($index, $item);
        }

        Utils::createLog(
            "The user has entered the update modal to commission row: ".$id,
            "commissions.calculation",
            "show"
        );

        return view("commissions.partials.updateUploadRowModal",[
            "commissionRow" => $commissionRow,
            "data" => $formattedData,
            "formattedData" => $formattedData,
        ]);
    }

    public function update($id, Request $request) {

        $commissionRow = CommissionUploadRowsModel::find($id);
        $data = json_decode($commissionRow->data, true);
        $excelBase = 25569;
        foreach ($data as $index => $value) {
            $endWord = explode("_",$index);
            $endWord = last($endWord);
            $value = $request->input("data_".$index);
            if($endWord == "date" && $request->has("data_".$index) && $request->input("data_".$index) !== null){
                $date = $request->input("data_".$index);
                $daysFrom1970 = intval(strtotime($date) / 86400);
                $value = $daysFrom1970 + $excelBase;
            }            
            $data[$index] = $value;
        }
        $commissionRow->data = json_encode($data);
        $commissionRow->save();

        Utils::createLog(
            "The user has updated the commission row: ".$id,
            "commissions.calculation",
            "update"
        );

        return redirect(route('commissions.calculation.showImport', ['id' => $commissionRow->fk_commission_upload]))->with('message', 'Commision row updated');
    }

    public function delete($id) {
        $commissionRow = CommissionUploadRowsModel::find($id);
        if (isset($commissionRow->transactions) && isset($commissionRow->transactions->statements)) {
            foreach($commissionRow->transactions->statements as $statementItem){
                if($statementItem->statement->status == 1){
                    return redirect(route('commissions.calculation.showImport', ['id' => $commissionRow->fk_commission_upload]))->with('error', "The row cannot be deleted, a statement has already been generated.");
                }
            }
        }
        $idComm = $commissionRow->fk_commission_upload;

        $commissionUpload = CommissionUploadsModel::find($idComm);
        $commissionUpload->uploaded_rows = $commissionUpload->uploaded_rows - 1;
        if($commissionRow->status == 2){
            $commissionUpload->processed_rows = $commissionUpload->processed_rows - 1;
        }
        else{
            $commissionUpload->error_rows = $commissionUpload->error_rows - 1;
            
        }
        $commissionUpload->save();
        $commissionRow->delete();

        Utils::createLog(
            "The user has deleted the commission row: ".$id,
            "commissions.calculation",
            "delete"
        );
        return redirect(route('commissions.calculation.showImport', ['id' => $commissionUpload->id]))->with('message', "The row deleted");

    }

    public function testRow($id)
    {
        $procesor = new CommissionRowProcessor();
        $procesor->startProcess($id);
    }
}
