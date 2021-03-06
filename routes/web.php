<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([ 'ns.installed' ])->group( function() {
    Route::get( '/sign-in', 'AuthController@signIn' )->name( 'ns.login' );
    Route::get( '/sign-up', 'AuthController@signUp' );
    Route::get( '/password-lost', 'AuthController@passwordLost' );
    Route::get( '/new-password', 'AuthController@newPassword' );

    Route::post( '/auth/sign-in', 'AuthController@postSignIn' );
    Route::get( '/sign-out', 'AuthController@signOut' )->name( 'ns.logout' );

    Route::middleware([ 'auth' ])->group( function() {
        Route::get( '/dashboard', 'DashboardController@home' )->name( 'dashboard.index' );
        Route::get( '/dashboard/orders', 'Dashboard\OrdersController@listOrders' );
        Route::get( '/dashboard/customers', 'Dashboard\CustomersController@listCustomers' );
        Route::get( '/dashboard/customers/create', 'Dashboard\CustomersController@createCustomer' );
        Route::get( '/dashboard/customers/edit/{customer}', 'Dashboard\CustomersController@editCustomer' );
        Route::get( '/dashboard/customers/groups', 'Dashboard\CustomersGroupsController@listCustomersGroups' );
        Route::get( '/dashboard/customers/groups/create', 'Dashboard\CustomersGroupsController@createCustomerGroup' );
        Route::get( '/dashboard/customers/groups/edit/{group}', 'Dashboard\CustomersGroupsController@editCustomerGroup' );
        
        Route::get( '/dashboard/customers/rewards-system', 'Dashboard\RewardsSystemController@list' );
        Route::get( '/dashboard/customers/rewards-system/create', 'Dashboard\RewardsSystemController@create' );
        Route::get( '/dashboard/customers/rewards-system/edit/{reward}', 'Dashboard\RewardsSystemController@edit' );

        Route::get( '/dashboard/customers/coupons', 'Dashboard\CustomersController@listCoupons' );
        Route::get( '/dashboard/customers/coupons/create', 'Dashboard\CustomersController@createCoupon' );
        Route::get( '/dashboard/customers/coupons/edit/{reward}', 'Dashboard\CustomersController@editCoupon' );

        Route::get( '/dashboard/providers', 'Dashboard\ProvidersController@listProvider' );
        Route::get( '/dashboard/providers/create', 'Dashboard\ProvidersController@createProvider' );
        Route::get( '/dashboard/providers/edit/{provider}', 'Dashboard\ProvidersController@editProvider' );

        Route::get( '/dashboard/expenses', 'Dashboard\ExpensesController@listExpenses' );
        Route::get( '/dashboard/expenses/categories', 'Dashboard\ExpensesCategoriesController@listExpensesCategories' );
        Route::get( '/dashboard/products', 'Dashboard\ProductsController@listProducts' );
        Route::get( '/dashboard/products/categories', 'Dashboard\CategoryController@listCategories' );
        Route::get( '/dashboard/products/units', 'Dashboard\UnitsController@listUnits' );
        Route::get( '/dashboard/products/units/groups', 'Dashboard\UnitsController@listUnitsGroups' );
        Route::get( '/dashboard/users', 'Dashboard\UsersController@listUsers' );
        Route::get( '/dashboard/profile', 'Dashboard\UsersController@showProfile' );

        Route::prefix( 'api/nexopos/v4' )->group( function() {
            foreach([ '', '/store/{id}/' ] as $prefix ) {
                Route::prefix( $prefix )->group( function() {
                    include_once( dirname( __FILE__ ) . '/api/categories.php' );    
                    include_once( dirname( __FILE__ ) . '/api/customers.php' );
                    include_once( dirname( __FILE__ ) . '/api/expenses.php' );
                    include_once( dirname( __FILE__ ) . '/api/orders.php' );
                    include_once( dirname( __FILE__ ) . '/api/procurements.php' );
                    include_once( dirname( __FILE__ ) . '/api/products.php' );
                    include_once( dirname( __FILE__ ) . '/api/providers.php' );
                    include_once( dirname( __FILE__ ) . '/api/registers.php' );
                    include_once( dirname( __FILE__ ) . '/api/reset.php' );
                    include_once( dirname( __FILE__ ) . '/api/rewards.php' );
                    include_once( dirname( __FILE__ ) . '/api/transfer.php' );
                    include_once( dirname( __FILE__ ) . '/api/taxes.php' );
                    include_once( dirname( __FILE__ ) . '/api/units.php' );
                    include_once( dirname( __FILE__ ) . '/api/crud.php' );
                });
            }
        });
    });


    include_once( dirname( __FILE__ ) . '/api/stores.php' );
});

Route::middleware([ 'ns.not-installed' ])->group( function() {
    Route::prefix( '/do-setup/' )->group( function() {
        Route::get( '', 'SetupController@welcome' )->name( 'setup' );
    });

    Route::prefix( 'api/nexopos/v4' )->group( function() {
        Route::prefix( 'setup' )->group( function() {
            Route::post( 'database', 'SetupController@checkDatabase' );
            Route::get( 'database', 'SetupController@checkDbConfigDefined' );
            Route::post( 'configuration', 'SetupController@saveConfiguration' );
        });
    });
});

Route::get( '/routes', function() {
    return ( array ) app( 'router' )->getRoutes();
});
