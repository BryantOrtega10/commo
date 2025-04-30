<?php

namespace App\Http\Controllers\Policies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Policies\CountiesRequest;
use App\Models\MultiTable\RegionsModel;
use App\Models\MultiTable\StatesModel;
use App\Models\Policies\CountiesModel;
use Exception;
use Illuminate\Http\Request;

class CountiesController extends Controller
{

    private $DEFAULTS_VALUES;

    public function __construct()
    {
        $this->DEFAULTS_VALUES = [
            "base-view" => "counties",
            "base-route" => "counties",
            "plural-title" => "Counties",
            "singular-title" => "Countie"
        ];
    }

    public function show()
    {
        $items = CountiesModel::get();
        return view($this->DEFAULTS_VALUES['base-view'] . '.show', [
            'items' => $items,
            'defaults' => $this->DEFAULTS_VALUES,
        ]);
    }

    public function showCreateForm()
    {
        $regions = RegionsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view($this->DEFAULTS_VALUES['base-view'] . '.create', [
            'defaults' => $this->DEFAULTS_VALUES,
            'regions' => $regions,
            'states' => $states
        ]);
    }

    public function create(CountiesRequest $request)
    {
        $item = new CountiesModel;
        $item->name = $request->input("name");
        $item->description = $request->input("description");
        $item->sort_order = $request->input("sort_order");
        $item->status = $request->input("status");
        $item->fk_state = $request->input("state");
        $item->fk_region = $request->input("region");
        $item->save();

        return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' created successfully');
    }


    public function showUpdateForm($id)
    {

        $item = CountiesModel::find($id);
        $regions = RegionsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        return view($this->DEFAULTS_VALUES['base-view'] . '.update', [
            'defaults' => $this->DEFAULTS_VALUES,
            'item' => $item,
            'regions' => $regions,
            'states' => $states
        ]);
    }


    public function update($id, CountiesRequest $request)
    {
        $item = CountiesModel::find($id);
        $item->name = $request->input("name");
        $item->description = $request->input("description");
        $item->sort_order = $request->input("sort_order");
        $item->status = $request->input("status");
        $item->fk_state = $request->input("state");
        $item->fk_region = $request->input("region");
        $item->save();

        return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' updated successfully');
    }


    public function delete($id)
    {
        try {
            CountiesModel::find($id)->delete();
            return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' deleted successfully');
        } catch (Exception $e) {
            return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('error', "This " . $this->DEFAULTS_VALUES['singular-title'] . " is related and cannot be deleted, if you wish you can change the " . $this->DEFAULTS_VALUES['singular-title'] . " status to inactive");
        }
    }


    public function details($id)
    {
        $item = CountiesModel::find($id);
        return response()->json([
            "success" => true,
            "item" => $item
        ]);
    }

    public function loadInfo($id = null)
    {
        if (empty($id)) {
            return response()->json([
                "success" => false,
                "message" => "Please send county ID"
            ], 400);
        }

        $county = CountiesModel::where("id", "=", $id)->with("state")->with("region")->first();

        return response()->json([
            "success" => true,
            "county" => $county
        ]);
    }
}
