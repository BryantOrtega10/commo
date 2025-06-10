<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Cuids\CreateCuidRequest;
use App\Http\Requests\Cuids\EditCuidRequest;
use App\Models\Customers\CuidsModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\CarriersModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CuidsController extends Controller
{
    public function showCreateForm($customerID)
    {
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        Utils::createLog(
            "The user has entered the form to create CUID to Customer with ID: " . $customerID,
            "customers.cuids",
            "show"
        );

        return view('cuids.create', [
            "customerID" => $customerID,
            "carriers" => $carriers,
            "business_segments" => $business_segments,
        ]);
    }

    public function create(CreateCuidRequest $request)
    {
        $cuid = new CuidsModel();
        $cuid->name = $request->input("cuid");
        $cuid->fk_carrier = $request->input("carrier");
        $cuid->fk_business_segment = $request->input("business_segment");
        $cuid->validation_date = $request->input("validation_date");
        $cuid->validation_note = $request->input("validation_note");
        $cuid->fk_customer = $request->input("customerID");
        $cuid->save();

        Utils::createLog(
            "The user has created a new CUID with ID: " . $cuid->id,
            "customers.cuids",
            "create"
        );


        return redirect(route('customers.update', ['id' => $cuid->fk_customer]))->with('message', 'CUID created successfully');
    }

    public function showUpdateForm($id)
    {

        $cuid = CuidsModel::find($id);
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        Utils::createLog(
            "The user has entered the form to update CUIDs with ID:" . $cuid->id,
            'customers.cuids',
            "show"
        );

        return view('cuids.update', [
            "cuid" => $cuid,
            "carriers" => $carriers,
            "business_segments" => $business_segments,
        ]);
    }


    public function update($id, EditCuidRequest $request)
    {
        $cuid = CuidsModel::find($id);
        $cuid->name = $request->input("cuid");
        $cuid->fk_carrier = $request->input("carrier");
        $cuid->fk_business_segment = $request->input("business_segment");
        $cuid->validation_date = $request->input("validation_date");
        $cuid->validation_note = $request->input("validation_note");
        $cuid->save();

        Utils::createLog(
            "The user has modified the CUID with ID:" . $cuid->id,
            'customers.cuids',
            "update"
        );

        return redirect(route('customers.update', ['id' => $cuid->fk_customer]))->with('message', 'CUID upadted successfully');
    }

    public function delete($id)
    {
        $cuid = CuidsModel::find($id);
        Utils::createLog(
            "The user has attempted to delete the CUID with ID: " . $id,
            'customers.cuids',
            "delete"
        );
        $customerID = $cuid->fk_customer;
        $cuid->delete();
        Utils::createLog(
            "The user has deleted the CUID with ID: " . $id,
            'customers.cuids',
            "delete"
        );

        return redirect(route('customers.update', ['id' => $customerID]))->with('message', 'CUID deleted successfully');
    }
}
