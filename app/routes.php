<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------.
 */
Route::model('user', 'User');
#Route::model('comment', 'Comment');
#Route::model('post', 'Post');
Route::model('role', 'Role');

/* ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */
//Route::pattern('comment', '[0-9]+');
//Route::pattern('post', '[0-9]+');
Route::pattern('user', '[0-9]+');
Route::pattern('role', '[0-9]+');
Route::pattern('token', '[0-9a-z]+');

/* ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function () {

    # User Management
    Route::get('users/{user}/show', 'AdminUsersController@getShow');
    Route::get('users/{user}/edit', 'AdminUsersController@getEdit');
    Route::post('users/{user}/edit', 'AdminUsersController@postEdit');
    Route::get('users/{user}/delete', 'AdminUsersController@getDelete');
    Route::post('users/{user}/delete', 'AdminUsersController@postDelete');
    Route::controller('users', 'AdminUsersController');

    # Dashboard Management
    Route::get('dashboard/', 'AdminDashboardController@getAdmin');
    Route::post('dashboard/', function () {
        GridEncoder::encodeRequestedData(new AdminDashboardRepository(new Ticket()), Input::all());
    });
    Route::post('request/update/', 'AdminDashboardController@updateRequest');

    # Report Management
    Route::get('report/', 'AdminReportController@getIndex');
    Route::get('report/demand', 'AdminReportController@getDemand');

    Route::post('report/{id}/stage', 'AdminReportController@getStage');
    Route::post('report/{id}/status', 'AdminReportController@getStatus');
    Route::post('report/{id}/role', 'AdminReportController@getRole');

    # User Role Management
    Route::get('roles/{role}/show', 'AdminRolesController@getShow');
    Route::get('roles/{role}/edit', 'AdminRolesController@getEdit');
    Route::post('roles/{role}/edit', 'AdminRolesController@postEdit');
    Route::get('roles/{role}/delete', 'AdminRolesController@getDelete');
    Route::post('roles/{role}/delete', 'AdminRolesController@postDelete');
    Route::controller('roles', 'AdminRolesController');

    # Admin Dashboard
    Route::controller('/', 'AdminDashboardController');

});

/* ------------------------------------------
 *  Frontend Routes
 *  ------------------------------------------
 */

// User reset routes
Route::get('user/reset/{token}', 'UserController@getReset');
// User password reset
Route::post('user/reset/{token}', 'UserController@postReset');
//:: User Account Routes ::
Route::post('user/{user}/edit', 'UserController@postEdit');

//:: User Account Routes ::
Route::post('user/login', 'UserController@postLogin');

# User RESTful Routes (Login, Logout, Register, etc)
Route::controller('user', 'UserController');

//:: Application Routes ::

# Index Page - Last route, no matches
Route::get('/', array('before' => 'detectLang', 'uses' => 'UserController@getIndex'));

#Requestor Controller
// User reset routes
//Resquest routes
Route::get('request', 'RequestController@getIndex');

Route::get('request/status', function () {
    // Return about us page
    return View::make('request/status');
});

// route to process the request form
Route::post('request/create', 'RequestController@store');
// request created success
Route::get('request/success/{ticket}', 'RequestController@success');



// Tickets
Route::group(array('prefix' => 'ticket', 'before' => 'auth'), function () {
    // Get resolved ticket view
    Route::get('status/{status_name}', 'TicketController@getTickets');
    // Get resolved ticket data
    Route::post('status/resolved', function () {
        GridEncoder::encodeRequestedData(new TicketRepository([3, new Ticket(), 1]), Input::all());
    });
    // Get closed ticket view
    Route::post('status/closed', function () {
        GridEncoder::encodeRequestedData(new TicketRepository([4, new Ticket(), 0]), Input::all());
    });
    // Get closed ticket view
    Route::post('status/rejected', function () {
        GridEncoder::encodeRequestedData(new TicketRepository([5, new Ticket(), 1]), Input::all());
    });
});



Route::group(array('prefix' => 'request', 'before' => 'auth'), function () {
    // Before CSRF checks : FIXME
    Route::post('update/', 'RequestController@updateRequest');
    Route::post('updatePhotographer/', 'RequestController@updatePhotographer');
    Route::post('updateMIF/', 'RequestController@updateMIF');
    // Route::post('updateEditingManager/', 'RequestController@updateEditingManager');
    Route::post('updateEditingTeamLead/', 'RequestController@updateEditingTeamLead');
    // Route::post('request/updateEditor/', 'RequestController@updateEditor');
    Route::post('updateEditingComplete/', 'RequestController@updateEditingComplete');
    Route::post('updateAssignCatalogTeamLead/', 'RequestController@updateAssignCatalogTeamLead');
    Route::post('updateCatalogTeamLead/', 'RequestController@updateCatalogTeamLead');
    Route::post('updateCataloger/', 'RequestController@updateCataloger');
    Route::post('updateCatalogingComplete/', 'RequestController@updateCatalogingComplete');
    Route::get('info/', function () {
        phpinfo();
    });
});

Route::group(array('prefix' => 'dashboard', 'before' => 'auth'), function () {
    Route::get('locallead/', 'DashboardController@getLocalLead');
    Route::get('photographer/', 'DashboardController@getPhotographer');
    Route::get('mif/', 'DashboardController@getMIF');
    // Route::get('editingmanager/', 'DashboardController@getEditingManager');
    Route::get('editingteamlead/', 'DashboardController@getEditingTeamLead');
    Route::get('editor/', 'DashboardController@getEditor');
    // Route::get('catalogmanager/', 'DashboardController@getCatalogManager');
    Route::get('catalogteamlead/', 'DashboardController@getCatalogTeamLead');
    Route::get('cataloger/', 'DashboardController@getCataloger');

    Route::post('locallead/', function () {
        GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
    });
    Route::post('photographer/', function () {
        GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
    });

    Route::post('mif', function () {
        GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
    });

    // Route::post('editingmanager/', function () {
    //     GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    // });

    Route::post('editingteamlead/', function () {
        GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    });

    Route::post('editor/', function () {
        GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    });

    // Route::post('cataloguemanager/', function () {
    //     GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    // });

    Route::post('catalogueteamlead/', function () {
        GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    });

    Route::post('cataloguer/', function () {
        GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
    });

    Route::post('seller/', 'DashboardController@postSeller');
    Route::post('sellerinfo/', 'DashboardController@postSellerInfo');
    Route::post('editing/', 'DashboardController@postEditing');

});


App::missing(function ($e) {
    $url = Request::fullUrl();
    Log::warning("404 for URL: $url");

    return Response::view('error/404', array(), 404);
});
