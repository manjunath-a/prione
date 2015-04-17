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
 *  ------------------------------------------
 */
Route::model('user', 'User');
#Route::model('comment', 'Comment');
#Route::model('post', 'Post');
Route::model('role', 'Role');

/** ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */
//Route::pattern('comment', '[0-9]+');
//Route::pattern('post', '[0-9]+');
Route::pattern('user', '[0-9]+');
Route::pattern('role', '[0-9]+');
Route::pattern('token', '[0-9a-z]+');

/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{

    # User Management
    Route::get('users/{user}/show', 'AdminUsersController@getShow');
    Route::get('users/{user}/edit', 'AdminUsersController@getEdit');
    Route::post('users/{user}/edit', 'AdminUsersController@postEdit');
    Route::get('users/{user}/delete', 'AdminUsersController@getDelete');
    Route::post('users/{user}/delete', 'AdminUsersController@postDelete');
    Route::controller('users', 'AdminUsersController');

    # Dashboard Management
    Route::get('dashboard/', 'AdminDashboardController@getAdmin');
    Route::post('dashboard/', function()
    {
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


/** ------------------------------------------
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

# Filter for detect language
Route::when('contact-us','detectLang');

# Contact Us Static Page
Route::get('contact-us', function()
{
    // Return about us page
    return View::make('site/contact-us');
});

# Index Page - Last route, no matches
Route::get('/', array('before' => 'detectLang','uses' => 'UserController@getIndex'));

#Requestor Controller
// User reset routes
//Resquest routes
Route::get('request', 'RequestController@getIndex');

Route::get('request/status', function()
{
    // Return about us page
    return View::make('request/status');
});

// route to process the request form
Route::post('request/create', 'RequestController@store');
// request created success
Route::get('request/success/{ticket}', 'RequestController@success');

Route::get('info/', function() {
    phpinfo();
});

// Before CSRF checks : FIXME
Route::post('request/update/', 'RequestController@updateRequest');
Route::post('request/updatePhotographer/', 'RequestController@updatePhotographer');
Route::post('request/updateMIF/', 'RequestController@updateMIF');
Route::post('request/updateEditingManager/', 'RequestController@updateEditingManager');
Route::post('request/updateEditingTeamLead/', 'RequestController@updateEditingTeamLead');
// Route::post('request/updateEditor/', 'RequestController@updateEditor');
Route::post('request/updateEditingComplete/', 'RequestController@updateEditingComplete');
Route::post('request/updateAssignCatalogTeamLead/', 'RequestController@updateAssignCatalogTeamLead');
Route::post('request/updateCatalogueTeamLead/', 'RequestController@updateCatalogueTeamLead');
Route::post('request/updateCataloguer/', 'RequestController@updateCataloguer');
Route::post('request/updateCatalogingComplete/', 'RequestController@updateCatalogingComplete');

Route::get('dashboard/locallead/', 'DashboardController@getLocalLead');
Route::get('dashboard/photographer/', 'DashboardController@getPhotographer');
Route::get('dashboard/mif/', 'DashboardController@getMIF');
Route::get('dashboard/editingmanager/', 'DashboardController@getEditingManager');
Route::get('dashboard/editingteamlead/', 'DashboardController@getEditingTeamLead');
Route::get('dashboard/editor/', 'DashboardController@getEditor');
Route::get('dashboard/cataloguemanager/', 'DashboardController@getCatalogueManager');
Route::get('dashboard/catalogueteamlead/', 'DashboardController@getCatalogueTeamLead');
Route::get('dashboard/cataloguer/', 'DashboardController@getCataloguer');


Route::post('/dashboard/locallead', function()
{
    GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
});
Route::post('/dashboard/photographer', function()
{
    GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/mif', function()
{
    GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/editingmanager', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/editingteamlead', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/editor', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/cataloguemanager', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/catalogueteamlead', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/cataloguer', function()
{
    GridEncoder::encodeRequestedData(new CentralDashboardRepository(new Ticket()), Input::all());
});

Route::post('/dashboard/seller', 'DashboardController@postSeller');

Route::post('/dashboard/editing', 'DashboardController@postEditing');



App::missing(function($e) {
    $url = Request::fullUrl();
    Log::warning("404 for URL: $url");
    return Response::view('error/404', array(), 404);
});

