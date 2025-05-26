<?php

use App\Http\Controllers\Agents\AgentNumbersController;
use App\Http\Controllers\Agents\AgentsControllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Commissions\AgentRatesController;
use App\Http\Controllers\Commissions\AgentReportController;
use App\Http\Controllers\Commissions\AgentReportProcessController;
use App\Http\Controllers\Commissions\AllSalesController;
use App\Http\Controllers\Commissions\CommissionRatesController;
use App\Http\Controllers\Commissions\CommissionsController;
use App\Http\Controllers\Commissions\TemplatesController;
use App\Http\Controllers\Commissions\UnlinkedErrorReportController;
use App\Http\Controllers\MultiTable\AgenciesController;
use App\Http\Controllers\MultiTable\AgencyCodesController;
use App\Http\Controllers\MultiTable\AgentStatusController;
use App\Http\Controllers\MultiTable\AgentTitlesController;
use App\Http\Controllers\MultiTable\BusinessSegmentsController;
use App\Http\Controllers\MultiTable\BusinessTypesController;
use App\Http\Controllers\MultiTable\CarriersController;
use App\Http\Controllers\MultiTable\ClientSourcesController;
use App\Http\Controllers\MultiTable\ContractTypeController;
use App\Http\Controllers\MultiTable\CustomerStatusController;
use App\Http\Controllers\MultiTable\EnrollmentMethodsController;
use App\Http\Controllers\MultiTable\GendersController;
use App\Http\Controllers\MultiTable\LegalBasisController;
use App\Http\Controllers\MultiTable\MaritalStatusController;
use App\Http\Controllers\MultiTable\MemberTypesController;
use App\Http\Controllers\MultiTable\PhasesController;
use App\Http\Controllers\MultiTable\PlanTypesController;
use App\Http\Controllers\MultiTable\PolicyAgentNumberTypesController;
use App\Http\Controllers\MultiTable\PolicyStatusController;
use App\Http\Controllers\MultiTable\ProductTypesController;
use App\Http\Controllers\MultiTable\RegionsController;
use App\Http\Controllers\MultiTable\RegistrationSourcesController;
use App\Http\Controllers\MultiTable\RelationshipsController;
use App\Http\Controllers\MultiTable\SalesRegionController;
use App\Http\Controllers\MultiTable\StatesController;
use App\Http\Controllers\MultiTable\SuffixesController;
use App\Http\Controllers\MultiTable\TiersController;

use App\Http\Controllers\Customers\CuidsController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Leads\ActivitiesController;
use App\Http\Controllers\Leads\LeadsController;
use App\Http\Controllers\Leads\MySettlementsController;
use App\Http\Controllers\MultiTable\AdminFeesController;

use App\Http\Controllers\Policies\CountiesController;
use App\Http\Controllers\Policies\PoliciesController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Utils\FilesController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

$crudRoutes = [
    "policies" => [
        'counties' => CountiesController::class,
        'enrollment-methods' => EnrollmentMethodsController::class,
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
        'sales-regions' => SalesRegionController::class,
        'admin-fees' => AdminFeesController::class
    ],
    "products" => [
        'business-segments' => BusinessSegmentsController::class,
        'business-types' => BusinessTypesController::class,
        'carriers' => CarriersController::class,
        'plan-types' => PlanTypesController::class,
        'product-tiers' => TiersController::class,
        'product-types' =>ProductTypesController::class
    ]
];

Auth::routes(['login' => false]);

Route::get('/', function () {
    if (Auth::check()){
        switch(strtolower(Auth::user()->role)){
            case 'admin':
                return redirect(route('policies.show'));
                break;
            case 'agent':
                return redirect(route('leads.show'));
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
        Route::post("/datatable", [CustomersController::class, 'datatableAjax'])->name("customers.datatable");
        Route::get("/create", [CustomersController::class, 'showCreateForm'])->name("customers.create");
        Route::post("/create", [CustomersController::class, 'create']);
        Route::get("/details/{id}", [CustomersController::class, 'showUpdateForm'])->name("customers.update");
        Route::post("/details/{id}", [CustomersController::class, 'update']);
    });

    Route::group(['prefix' => 'cuids'], function () {
        Route::get("/{customerID}", [CuidsController::class, 'showCreateForm'])->name("cuids.create");
        Route::post("/{customerID}", [CuidsController::class, 'create']);
        Route::get("/update/{id}", [CuidsController::class, 'showUpdateForm'])->name("cuids.update");
        Route::post("/update/{id}", [CuidsController::class, 'update']);
        Route::post("/delete/{id}", [CuidsController::class, 'delete'])->name("cuids.delete");
    });
    
});

Route::group([ 'prefix' => 'agents', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'agents'], function () {
        Route::get("/", [AgentsControllers::class, 'show'])->name("agents.show");
        Route::post("/", [AgentsControllers::class, 'show']);
        Route::post("/datatable", [AgentsControllers::class, 'datatableAjax'])->name("agents.datatable");
        Route::post("/search", [AgentsControllers::class, 'search'])->name("agents.search");
        Route::get("/create", [AgentsControllers::class, 'showCreateForm'])->name("agents.create");
        Route::post("/create", [AgentsControllers::class, 'create']);
        Route::get("/details/{id}", [AgentsControllers::class, 'showUpdateForm'])->name("agents.update");
        Route::post("/details/{id}", [AgentsControllers::class, 'update']);
    });
    Route::group(['prefix' => 'agent-numbers'], function () {
        Route::get("/{id}", [AgentNumbersController::class, 'showCreateForm'])->name("agent_numbers.create");
        Route::post("/{id}", [AgentNumbersController::class, 'create']);
        Route::get("/update/{id}", [AgentNumbersController::class, 'showUpdateModalForm'])->name("agent_numbers.updateModal");
        Route::post("/update/{id}", [AgentNumbersController::class, 'updateModal']);
        Route::post("/delete/{id}", [AgentNumbersController::class, 'delete'])->name("agent_numbers.delete");

        Route::get("/details/{id}", [AgentNumbersController::class, 'showUpdateForm'])->name("agent_numbers.update");
        Route::post("/details/{id}", [AgentNumbersController::class, 'updateForm']);
    });
});


Route::group([ 'prefix' => 'leads', 'middleware' => ['auth', 'user-role:agent']],function () {

    

    Route::get("/", [LeadsController::class, 'show'])->name("leads.show");
    Route::post("/datatable", [LeadsController::class, 'datatableAjax'])->name("leads.datatable");
    Route::get("/create", [LeadsController::class, 'showCreateForm'])->name("leads.create");
    Route::post("/create", [LeadsController::class, 'create']);
    Route::get("/details/{id}", [LeadsController::class, 'showDetailsForm'])->name("leads.details");
    Route::post("/details/{id}", [LeadsController::class, 'updateDetails']);
    Route::get("/update/{id}", [LeadsController::class, 'showUpdateForm'])->name("leads.update");
    Route::post("/update/{id}", [LeadsController::class, 'update']);

    Route::group(['prefix' => 'activity'], function () {
        Route::get("/{idLead}/{type}", [ActivitiesController::class, 'showActivityModal'])->name("leads.activityModal");
        Route::post("/{idLead}", [ActivitiesController::class, 'createActivity'])->name("leads.createActivity");
        Route::get("/modal/details/{id}", [ActivitiesController::class, 'showActivityDetailsModal'])->name("leads.activityDetailsModal");
        Route::post("/modal/details/{id}", [ActivitiesController::class, 'update']);

        Route::get("/notifications", [ActivitiesController::class, 'myNotifications'])->name("leads.notifications");
        Route::post("/notifications", [ActivitiesController::class, 'myNotifications'])->name("leads.notifications");
        
    });
});

Route::group([ 'prefix' => 'my-settlements', 'middleware' => ['auth', 'user-role:agent']],function () {
    Route::get("/", [MySettlementsController::class, 'show'])->name("my-settlements.show");
});

Route::group([ 'prefix' => 'products', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get("/", [ProductsController::class, 'show'])->name("products.show");
        Route::post("/datatable", [ProductsController::class, 'datatableAjax'])->name("products.datatable");
        Route::get("/create", [ProductsController::class, 'showCreateForm'])->name("products.create");
        Route::post("/create", [ProductsController::class, 'create']);
        Route::get("/update/{id}", [ProductsController::class, 'showUpdateForm'])->name("products.update");
        Route::post("/update/{id}", [ProductsController::class, 'update']);
    });
});

Route::group([ 'prefix' => 'policies', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'policies'], function () {
        Route::get("/", [PoliciesController::class, 'show'])->name("policies.show");
        Route::post("/datatable", [PoliciesController::class, 'datatableAjax'])->name("policies.datatable");
        Route::get("/create", [PoliciesController::class, 'showCreateForm'])->name("policies.create");
        Route::post("/create", [PoliciesController::class, 'create']);
        Route::get("/details/{id}", [PoliciesController::class, 'showUpdateForm'])->name("policies.update");
        Route::post("/details/{id}", [PoliciesController::class, 'update']);
    });
});


Route::group([ 'prefix' => 'commissions', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'calculation'], function () {
        Route::get("/", [CommissionsController::class, 'show'])->name("commissions.calculation");        
        
        Route::get("/template/{id?}", [CommissionsController::class, 'infoTemplate'])->name("commissions.calculation.infoTemplate");        
        Route::post("/import", [CommissionsController::class, 'import'])->name("commissions.calculation.import");        
        Route::get("/import/{id}", [CommissionsController::class, 'showImport'])->name("commissions.calculation.showImport");
        Route::get("/rows/{id}", [CommissionsController::class, 'loadUploadedRows'])->name("commissions.calculation.loadUploadedRows");
        Route::post("/datatable/{id}", [CommissionsController::class, 'datatableAjax'])->name("commissions.calculation.datatable");
        Route::get("/link-all/{id}", [CommissionsController::class, 'linkAllCommissions'])->name("commissions.calculation.linkAll");
        
        Route::post("/link", [CommissionsController::class, 'linkCommissions'])->name("commissions.calculation.link");

        Route::get("/link-errors/{id}", [CommissionsController::class, 'linkErrors'])->name("commissions.calculation.linkErrors");
        Route::get("/statements/{id}", [CommissionsController::class, 'showModalStatements'])->name("commissions.calculation.showStatements");

        Route::get("/update/{id}", [CommissionsController::class, 'showUpdateRow'])->name("commissions.calculation.update");
        Route::post("/update/{id}", [CommissionsController::class, 'update']);
        Route::post("/delete/{id}", [CommissionsController::class, 'delete'])->name("commissions.calculation.delete");

        Route::get("/test-row/{id}", [CommissionsController::class, 'testRow'])->name("commissions.calculation.testRow");
    });
    Route::group(['prefix' => 'rate'], function () {
        Route::get("/add-new-row", [CommissionRatesController::class, 'showCreateRow'])->name("commissions.rate.add-new");
        Route::post("/create", [CommissionRatesController::class, 'create'])->name("commissions.rate.create");
        Route::get("/update/{id}", [CommissionRatesController::class, 'showUpdateRow'])->name("commissions.rate.update");
        Route::post("/update/{id}", [CommissionRatesController::class, 'update']);
        Route::post("/delete/{id}", [CommissionRatesController::class, 'delete'])->name("commissions.rate.delete");
    });

    Route::group(['prefix' => 'agent-report'], function () {
        Route::get("/", [AgentReportController::class, 'showAgentReport'])->name("commissions.agent-report.show");
        Route::post("/", [AgentReportController::class, 'generateAgentReport']);
        Route::post("/datatable", [AgentReportController::class, 'dataTableAgentReport'])->name("commissions.agent-report.datatable");
    });

    Route::group(['prefix' => 'agent-process'], function () {
        Route::get("/", [AgentReportProcessController::class, 'showAgentReportProcesses'])->name("commissions.agent-process.show");
        Route::get("/show-batch/{id}", [AgentReportProcessController::class, 'showAgentReportProcessesBatch'])->name("commissions.agent-process.showBatch");
        Route::get("/email-template", [AgentReportProcessController::class, 'showAgentReportEmailTemplate'])->name("commissions.agent-process.show-email");
        Route::post("/email-template", [AgentReportProcessController::class, 'updateEmailTemplate']);
        Route::post("/batch", [AgentReportProcessController::class, 'generateAgentReportBatch'])->name("commissions.agent-process.generate-batch");
        Route::post("/individual", [AgentReportProcessController::class, 'generateAgentReportIndividual'])->name("commissions.agent-process.generate-individual");
        Route::post("/send-mail-batch", [AgentReportProcessController::class, 'sendMailBatch'])->name("commissions.agent-process.send-mail-batch");
        Route::post("/send-mail-individual", [AgentReportProcessController::class, 'sendMailIndividual'])->name("commissions.agent-process.send-mail-individual");
    });

    Route::group(['prefix' => 'agent-rates'], function () {
        Route::get("/", [AgentRatesController::class, 'showAgentRates'])->name("commissions.agent-rates.show");
        Route::post("/datatable", [AgentRatesController::class, 'datatable'])->name("commissions.agent-rates.datatable");
        Route::post("/append", [AgentRatesController::class, 'appendRates'])->name("commissions.agent-rates.append");
        Route::post("/replicate", [AgentRatesController::class, 'replicateRates'])->name("commissions.agent-rates.replicate");
    });

    Route::group(['prefix' => 'all-sales'], function () {
        Route::get("/", [AllSalesController::class, 'showAllSalesForm'])->name("commissions.all-sales.show");
        Route::post("/", [AllSalesController::class, 'generateAllSalesReport']);
        
    });

    Route::group(['prefix' => 'unlinked'], function () {
        Route::get("/", [UnlinkedErrorReportController::class, 'showUnlinkedErrorReport'])->name("commissions.unlinked.show");
        Route::post("/", [UnlinkedErrorReportController::class, 'generateUnlinkedErrorReport']);
        
    });

    


    

});


//Utils
Route::group([ 'prefix' => 'customers', 'middleware' => ['auth', 'user-role:admin|agent']],function () {
    Route::group(['prefix' => 'customers'], function () {
        Route::post("/search", [CustomersController::class, 'search'])->name("customers.search");
        Route::post("/search/subscribers", [CustomersController::class, 'searchSubscribers'])->name("customers.searchSubscribers");
        
    });
});
Route::group([ 'prefix' => 'products', 'middleware' => ['auth', 'user-role:admin|agent']],function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get("/details/{id?}", [ProductsController::class, 'loadInfo'])->name("products.loadInfo");
    });
});
Route::group([ 'prefix' => 'policies', 'middleware' => ['auth', 'user-role:admin|agent']],function () {
    Route::group(['prefix' => 'counties'], function () {
        Route::get("/{id?}", [CountiesController::class, 'loadInfo'])->name("counties.loadInfo");
    });
});

Route::group([ 'prefix' => 'users', 'middleware' => ['auth', 'user-role:admin|agent']],function () {
    Route::post("/change-password", [UsersController::class, 'changePassword'])->name("users.changePassword");
    Route::get("/my-profile", [UsersController::class, 'showProfileForm'])->name("users.profile");
    Route::post("/my-profile", [UsersController::class, 'updateProfile']);
    
});
Route::group([ 'prefix' => 'commissions', 'middleware' => ['auth', 'user-role:admin']],function () {
    Route::group(['prefix' => 'templates'], function () {
        Route::get("/details/{id?}", [TemplatesController::class, 'loadInfo'])->name("templates.loadInfo");
    });
});

Route::group([ 'prefix' => 'files', 'middleware' => ['auth', 'user-role:admin|agent']],function () {
    Route::post("/upload", [FilesController::class, 'uploadFile'])->name("files.upload");
    Route::post("/remove/{id}", [FilesController::class, 'remove'])->name("files.delete");
});


Route::get("storage-link", function () {
    File::link(
        storage_path('app/public'),
        public_path('storage')
    );
});
