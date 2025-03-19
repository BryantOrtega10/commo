<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultipleTableRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MultipleTableController extends Controller
{
    private $MODEL;
    private $DEFAULTS_VALUES;


    public function __construct(string $model, string $baseView, string $baseRoute, string $pluralTitle, string $singularTitle) {
        $this->MODEL = $model;
        $this->DEFAULTS_VALUES = [
            "base-view" => $baseView,
            "base-route" => $baseRoute,
            "plural-title" => $pluralTitle,
            "singular-title" => $singularTitle
        ];
    }

    public function show(){
        $items = $this->MODEL::get();
        return view($this->DEFAULTS_VALUES['base-view'].'.show',[
            'items' => $items,
            'defaults' => $this->DEFAULTS_VALUES,
        ]);
    }

    public function showCreateForm(){
        return view($this->DEFAULTS_VALUES['base-view'].'.create',[
            'defaults' => $this->DEFAULTS_VALUES,
        ]);
    }
    
    public function create(MultipleTableRequest $request){
        $item = new $this->MODEL;
        $item->name = $request->input("name");
        $item->description = $request->input("description");
        $item->sort_order = $request->input("sort_order");
        $item->status = $request->input("status");
        $item->save();

        return redirect(route($this->DEFAULTS_VALUES['base-route'].'.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'].' created successfully');
    }


    public function showUpdateForm($id){

        $item = $this->MODEL::find($id);

        return view($this->DEFAULTS_VALUES['base-view'].'.update',[
            'defaults' => $this->DEFAULTS_VALUES,
            'item' => $item
        ]);
    }


    public function update($id, MultipleTableRequest $request){
        $item = $this->MODEL::find($id);
        $item->name = $request->input("name");
        $item->description = $request->input("description");
        $item->sort_order = $request->input("sort_order");
        $item->status = $request->input("status");
        $item->save();

        return redirect(route($this->DEFAULTS_VALUES['base-route'].'.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'].' updated successfully');
    }


    public function delete($id){
        try{
            $this->MODEL::find($id)->delete();
            return redirect(route($this->DEFAULTS_VALUES['base-route'].'.show'))->with('message', $this->DEFAULTS_VALUES['singular-title'].' deleted successfully');
        }catch(Exception $e){
            return redirect(route($this->DEFAULTS_VALUES['base-route'].'.show'))->with('error', "This ".$this->DEFAULTS_VALUES['singular-title']." is related and cannot be deleted, if you wish you can change the ".$this->DEFAULTS_VALUES['singular-title']." status to inactive");
        }        
    }
    

    public function details($id){

        $item = $this->MODEL::find($id);
        
        return response()->json([
            "success" => true,
            "item" => $item
        ]);
    }
    
}
