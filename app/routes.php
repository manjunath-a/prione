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

    // # Request Management
    // Route::get('sellerrequest/{request}/show', 'AdminRequestController@getShow');
    // Route::get('sellerrequest/{request}/edit', 'AdminRequestController@getEdit');
    // Route::post('sellerrequest/{request}/edit', 'AdminRequestController@postEdit');
    // Route::get('sellerrequest/{request}/delete', 'AdminRequestController@getDelete');
    // Route::post('sellerrequest/{request}/delete', 'AdminRequestController@postDelete');
    // Route::controller('sellerrequest', 'AdminRequestController');


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
Route::get('request', 'RequestController@getIndex');

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

Route::get('dashboard/locallead/', 'DashboardController@getLocalLead');
Route::get('dashboard/photographer/', 'DashboardController@getPhotographer');
Route::get('dashboard/mif/', 'DashboardController@getMIF');
Route::get('dashboard/editingmanager/', 'DashboardController@getEditingManager');

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
    GridEncoder::encodeRequestedData(new DashboardRepository(new Ticket()), Input::all());
});


Route::post('/dashboard/seller', 'DashboardController@postSeller');

App::missing(function($e) {
    $url = Request::fullUrl();
    Log::warning("404 for URL: $url");
    return Response::view('error/404', array(), 404);
});

