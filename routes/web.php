<?php

use App\Http\Controllers\AgenciesController;
use App\Http\Controllers\AgencyCodesController;
use App\Http\Controllers\AgentStatusController;
use App\Http\Controllers\AgentTitlesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BusinessSegmentsController;
use App\Http\Controllers\BusinessTypesController;
use App\Http\Controllers\CarriersController;
use App\Http\Controllers\ClientSourcesController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CountiesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CustomerStatusController;
use App\Http\Controllers\EnrollmentMethodsController;
use App\Http\Controllers\GendersController;
use App\Http\Controllers\LegalBasisController;
use App\Http\Controllers\MaritalStatusController;
use App\Http\Controllers\MemberTypesController;
use App\Http\Controllers\PhasesController;
use App\Http\Controllers\PlanTypesController;
use App\Http\Controllers\PolicyAgentNumberTypesController;
use App\Http\Controllers\PolicyStatusController;
use App\Http\Controllers\ProductTypesController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\RegistrationSourcesController;
use App\Http\Controllers\RelationshipsController;
use App\Http\Controllers\SalesRegionController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\SuffixesController;
use App\Http\Controllers\TiersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$crudRoutes = [
    "policies" => [
        'client-sources' => ClientSourcesController::class,
        'counties' => CountiesController::class,
        'enrollment-methods' => EnrollmentMethodsController::class,
        'policy-agent-number-types' => PolicyAgentNumberTypesController::class,
        'policy-member-types' => MemberTypesController::class,
        'policy-status' => PolicyStatusController::class,
        'relationships' => RelationshipsController::class
    ],
    "customers" => [
        'genders' => GendersController::class,
        'marital-status' => MaritalStatusController::class,
        'regions' => RegionsController::class,
        'states' => StatesController::class,
        'suffixes' => SuffixesController::class,
        'customer-status' => CustomerStatusController::class,
        'phases' => PhasesController::class,
        'legal-basis' => LegalBasisController::class,
        'registration-sources' => RegistrationSourcesController::class,
    ],
    "agents" => [
        'agencies' => AgenciesController::class,
        'agency-codes' => AgencyCodesController::class,
        'contract-types' => ContractTypeController::class,
        'agent-status' => AgentStatusController::class,
        'agent-titles' => AgentTitlesController::class,
        'sales-regions' => SalesRegionController::class
    ],
    "products" => [
        'business-segments' => BusinessSegmentsController::class,
        'business-types' => BusinessTypesController::class,
        'carriers' => CarriersController::class,
        'plan-types' => PlanTypesController::class,
        'tiers' => TiersController::class,
        'types' =>ProductTypesController::class
    ]
];

Auth::routes(['login' => false]);

Route::get('/', function () {

    if (Auth::check()){
        switch(strtolower(Auth::user()->role)){
            case 'admin':
                return redirect(route('client-sources.show'));
                break;
        }
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

foreach($crudRoutes as $routeGroup => $subRoutes){
    Route::group([ 'prefix' => $routeGroup, 'middleware' => ['auth', 'user-role:admin']],function () use($subRoutes) {
        foreach($subRoutes as $routeItem => $controller) {
            Route::group([
                'prefix' => $routeItem
            ], function () use($routeItem, $controller) {
                Route::get("/", [$controller, 'show'])->name($routeItem.".show");
                Route::get("/create", [$controller, 'showCreateForm'])->name($routeItem.".create");
                Route::post("/create", [$controller, 'create']);
                Route::get("/update/{id}", [$controller, 'showUpdateForm'])->name($routeItem.".update");
                Route::post("/update/{id}", [$controller, 'update']);
                Route::post("/delete/{id}", [$controller, 'delete'])->name($routeItem.".delete");
                Route::get("/details/{id}", [$controller, 'details'])->name($routeItem.".details");
            });
        }    
    });
}

Route::group([ 'prefix' => 'customers', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'customers'], function () {
        Route::get("/", [CustomersController::class, 'show'])->name("customers.show");
        Route::post("/search", [CustomersController::class, 'search'])->name("customers.search");
        Route::get("/create", [CustomersController::class, 'showCreateForm'])->name("customers.create");
        Route::post("/create", [CustomersController::class, 'create']);
    });
});

//Utils
Route::group([ 'prefix' => 'customers', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'customers'], function () {
        Route::post("/search", [CustomersController::class, 'search'])->name("customers.search");
    });
});
Route::group([ 'prefix' => 'policies', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'counties'], function () {
        Route::get("/{id?}", [CountiesController::class, 'loadInfo'])->name("counties.loadInfo");
    });
});