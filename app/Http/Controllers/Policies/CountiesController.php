<?php

namespace App\Http\Controllers\Policies;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
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

        Utils::createLog(
            "The user has entered the ".$this->DEFAULTS_VALUES['plural-title']." list",
            $this->DEFAULTS_VALUES['base-route'],
            "show"
        );

        return view($this->DEFAULTS_VALUES['base-view'] . '.show', [
            'items' => $items,
            'defaults' => $this->DEFAULTS_VALUES,
        ]);
    }

    public function showCreateForm()
    {
        $regions = RegionsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $states = StatesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        Utils::createLog(
            "The user has entered the form to create ".$this->DEFAULTS_VALUES['plural-title'],
            $this->DEFAULTS_VALUES['base-route'],
            "show"
        );

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

        Utils::createLog(
            "The user has created a new ".$this->DEFAULTS_VALUES['singular-title'],
            $this->DEFAULTS_VALUES['base-route'],
            "create"
        );


        return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' created successfully');
    }


    public function showUpdateForm($id)
    {

        
        $item = CountiesModel::find($id);
        Utils::createLog(
            "The user has entered the form to update ".$this->DEFAULTS_VALUES['plural-title']. " with ID:".$id." and Name".$item->name,
            $this->DEFAULTS_VALUES['base-route'],
            "show"
        );
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

        Utils::createLog(
            "The user has modified the ".$this->DEFAULTS_VALUES['singular-title']." with ID:".$id." and name".$item->name,
            $this->DEFAULTS_VALUES['base-route'],
            "update"
        );

        return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' updated successfully');
    }


    public function delete($id)
    {
        

        try {
            Utils::createLog(
                "The user has attempted to delete the ".$this->DEFAULTS_VALUES['singular-title']." with ID: ".$id,
                $this->DEFAULTS_VALUES['base-route'],
                "delete"
            );
            
            CountiesModel::find($id)->delete();

            Utils::createLog(
                "The user has deleted the ".$this->DEFAULTS_VALUES['singular-title']." with ID: ".$id,
                $this->DEFAULTS_VALUES['base-route'],
                "delete"
            );
            return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'] . ' deleted successfully');
        } catch (Exception $e) {
            return redirect(route($this->DEFAULTS_VALUES['base-route'] . '.show'))->with('error', "This " . $this->DEFAULTS_VALUES['singular-title'] . " is related and cannot be deleted, if you wish you can change the " . $this->DEFAULTS_VALUES['singular-title'] . " status to inactive");
        }
    }


    public function details($id)
    {
        Utils::createLog(
            "The user saw details of the ".$this->DEFAULTS_VALUES['singular-title']. " with ID:".$id,
            $this->DEFAULTS_VALUES['base-route'],
            "show"
        );
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
