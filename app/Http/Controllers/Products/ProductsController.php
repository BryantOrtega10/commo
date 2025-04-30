<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductModel;
use App\Http\Requests\Products\UpdateProductModel;
use App\Models\MultiTable\BusinessSegmentsModel;
use App\Models\MultiTable\BusinessTypesModel;
use App\Models\MultiTable\CarriersModel;
use App\Models\MultiTable\PlanTypesModel;
use App\Models\MultiTable\ProductTypesModel;
use App\Models\MultiTable\TiersModel;
use App\Models\Products\ProductAliasModel;
use App\Models\Products\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function show(Request $request){

        $products = ProductsModel::select("products.*");

        if($request->has("plan_type") && !empty($request->input('plan_type'))){
            $products->join("plan_types","plan_types.id", "=", "products.fk_plan_type");
        }

        if($request->has("product_type") && !empty($request->input('product_type'))){
            $products->join("product_types","product_types.id", "=", "products.fk_product_type");
        }


        if($request->has("description") && !empty($request->input('description'))){

            $products->where("description","LIKE","%".$request->input('description')."%");
        }
        if($request->has("carrier") && !empty($request->input('carrier'))){
            $products->where("fk_carrier","=",$request->input('carrier'));
        }
        if($request->has("business_type") && !empty($request->input('business_type'))){
            $products->where("fk_business_type","=",$request->input('business_type'));
        }

        if($request->has("plan_type") && !empty($request->input('plan_type'))){
            $products->where("plan_types.name", "LIKE","%".$request->input('plan_type')."%");
        }

        if($request->has("product_type") && !empty($request->input('product_type'))){
            $products->where("product_types.name", "LIKE","%".$request->input('product_type')."%");
        }
        
        $products = $products->get();

        session()->flashInput($request->all());

        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();

        
        return view('products.show', [
            "products" => $products,
            "business_types" => $business_types,
            "carriers" => $carriers,
        ]);
    }
    
    public function datatableAjax(Request $request)
    {
        $products = ProductsModel::select("products.*");
        $products->leftJoin("carriers", "carriers.id", "=", "products.fk_carrier");
        $products->leftJoin("business_segments", "business_segments.id", "=", "products.fk_business_segment");
        $products->leftJoin("business_types", "business_types.id", "=", "products.fk_business_type");
        $products->leftJoin("product_types", "product_types.id", "=", "products.fk_product_type");
        $products->leftJoin("plan_types", "plan_types.id", "=", "products.fk_plan_type");
        $products->leftJoin("tiers", "tiers.id", "=", "products.fk_tier");
       


        if($request->has("description") && !empty($request->input('description'))){

            $products->where("description","LIKE","%".$request->input('description')."%");
        }
        if($request->has("carrier") && !empty($request->input('carrier'))){
            $products->where("fk_carrier","=",$request->input('carrier'));
        }
        if($request->has("business_type") && !empty($request->input('business_type'))){
            $products->where("fk_business_type","=",$request->input('business_type'));
        }

        if($request->has("plan_type") && !empty($request->input('plan_type'))){
            $products->where("plan_types.name", "LIKE","%".$request->input('plan_type')."%");
        }

        if($request->has("product_type") && !empty($request->input('product_type'))){
            $products->where("product_types.name", "LIKE","%".$request->input('product_type')."%");
        }


        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $products->where(function($query) use ($searchTxt) {
                $query->where("products.description","like","%{$searchTxt}%")
                    ->orWhere("carriers.name", "like", "%{$searchTxt}%")
                    ->orWhere("business_segments.name", "like", "%{$searchTxt}%")
                    ->orWhere("business_types.name", "like", "%{$searchTxt}%")
                    ->orWhere("product_types.name", "like", "%{$searchTxt}%")
                    ->orWhere("plan_types.name", "like", "%{$searchTxt}%")
                    ->orWhere("tiers.name", "like", "%{$searchTxt}%");
                      
            });
        }

        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $products->orderBy("products.description",$direction);
                    break;
                case '1':
                    $products->orderBy("carriers.name",$direction);
                    break;
                case '2':
                    $products->orderBy("business_segments.name",$direction);
                    break;
                case '3':
                    $products->orderBy("business_types.name",$direction);
                    break;
                case '4':
                    $products->orderBy("product_types.name",$direction);
                    break;
                case '5':
                    $products->orderBy("plan_types.name",$direction);
                    break;
                case '6':
                    $products->orderBy("tiers.name",$direction);
                    break;
               
            }
        }
        
        $totalRecords = $products->count();
        $products = $products->skip($request->input('start'))
                            ->take($request->input('length'))
                            ->get();

        $filteredRecords = array();

        foreach($products as $product){            
            $filteredRecord = array();
            $filteredRecord["description"]["text"] = $product->description;
            $filteredRecord["description"]["href"] = route('products.update',['id' => $product->id]);
            $filteredRecord["carriers"] = $product->carrier?->name ?? "";
            $filteredRecord["business_segments"] = $product->business_segment?->name ?? "";
            $filteredRecord["business_types"] = $product->business_type?->name ?? "";
            $filteredRecord["product_types"] = $product->product_type?->name ?? "";
            $filteredRecord["plan_types"] = $product->plan_type?->name ?? "";
            $filteredRecord["tiers"] = $product->tier?->name ?? "";
            array_push($filteredRecords, $filteredRecord);
        }
       

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);
    }

    public function showCreateForm()
    {
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $tiers = TiersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        
        return view('products.create', [
            "carriers" => $carriers,
            "business_segments" => $business_segments,
            "business_types" => $business_types,
            "product_types" => $product_types,
            "plan_types" => $plan_types,
            "tiers" => $tiers,
        ]);
    }

    public function create(CreateProductModel $request){

        $entry_user = Auth::user();
        
        $product = new ProductsModel();
        $product->description = $request->input("description");
        $product->fk_carrier = $request->input("carrier");
        $product->fk_business_segment = $request->input("business_segment");
        $product->fk_business_type = $request->input("business_type");
        $product->fk_product_type = $request->input("product_type");
        $product->fk_plan_type = $request->input("plan_type");
        $product->fk_tier = $request->input("tier");
        $product->fk_entry_user = $entry_user->id;
        $product->save();

        if($request->has("alias")){
            foreach($request->input("alias") as $aliasTxt){
                $productAlias = new ProductAliasModel();
                $productAlias->alias = $aliasTxt;
                $productAlias->fk_product = $product->id;
                $productAlias->save();
            }
        }

        return redirect(route('products.show'))->with('message', 'Product created successfully');
    }

    public function showUpdateForm($id){
        $product = ProductsModel::find($id);
        
        $carriers = CarriersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_segments = BusinessSegmentsModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $business_types = BusinessTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $product_types = ProductTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $plan_types = PlanTypesModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();
        $tiers = TiersModel::where("status", "=", "1")->orderBy("sort_order", "ASC")->get();


        return view('products.update', [
            "product" => $product,
            "carriers" => $carriers,
            "business_segments" => $business_segments,
            "business_types" => $business_types,
            "product_types" => $product_types,
            "plan_types" => $plan_types,
            "tiers" => $tiers,
        ]);
    }

    public function update($id, UpdateProductModel $request){

        $product = ProductsModel::find($id);
        $product->description = $request->input("description");
        $product->fk_carrier = $request->input("carrier");
        $product->fk_business_segment = $request->input("business_segment");
        $product->fk_business_type = $request->input("business_type");
        $product->fk_product_type = $request->input("product_type");
        $product->fk_plan_type = $request->input("plan_type");
        $product->fk_tier = $request->input("tier");
        $product->save();

        $arrAlias = [];
        if($request->has("aliasIDs")){
            $arrAlias = $request->input("aliasIDs");
        }
        foreach($product->product_alias as $aliasBD){
            if(!in_array($aliasBD->id, $arrAlias)){
                $aliasBD->delete();
            }
        }


        if($request->has("alias")){
            foreach($request->input("alias") as $index => $aliasTxt){
                $aliasID = $arrAlias[$index] ?? null;
                if($aliasID == null){
                    $productAlias = new ProductAliasModel();
                }
                else{
                    $productAlias = ProductAliasModel::find($aliasID);
                }

                $productAlias->alias = $aliasTxt;
                $productAlias->fk_product = $product->id;
                $productAlias->save();
            }
        }

        return redirect(route('products.show'))->with('message', 'Product updated successfully');
    }

    public function loadInfo($id = null){
        if($id){
            $product = ProductsModel::find($id);
            return response()->json([
                "carrier" => $product->carrier?->name,
                "plan_type" => $product->plan_type?->name,
                "product_type" => $product->product_type?->name,
                "tier" => $product->tier?->name,
                "business_segment" => $product->business_segment?->name,
                "business_type" => $product->business_type?->name,
            ]);
        }
        return response()->json([]);
    }
}
