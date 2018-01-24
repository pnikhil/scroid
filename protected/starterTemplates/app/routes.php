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

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

Route::get('@@plural@@', array('as' => '@@plural@@', 'uses' => '@@singular-pascalCase@@Controller@index'));
Route::get('@@plural@@/popular', array('as' => 'popular@@plural-pascalCase@@', 'uses' => '@@singular-pascalCase@@Controller@popular'));
Route::get('@@plural@@/{nameString}/{@@singular@@Id}', array('as' => 'view@@singular-pascalCase@@', 'uses' => '@@singular-pascalCase@@Controller@view@@singular-pascalCase@@'));
Route::get('pages/{nameString}.html', array('as' => 'viewPage', 'uses' => 'PageController@viewPage'));
Route::get('category/{slug}', array('as' => 'category', 'uses' => '@@singular-pascalCase@@Controller@category'));

Route::filter('iluvcricketAuth', function()
{
    $iluvcricket = Session::get('iluvcricket');
    /*if (!$iluvcricket && !Input::get('logmein'))
    {
        return Response::notFound();
    } else */

    if(!$iluvcricket) {
        if(Request::ajax()) {
            return Response::make('You have been logged out or your session has expired. Please login on another tab and try again.<br><br><a target="_blank" href="'. route('iluvcricketLogin') .'" class="btn btn-success">Login again</a></a>', 400);
        } else{
            return Redirect::route('iluvcricketLogin', ['redirect' => urlencode(Request::path())]);
        }
    }
});

Route::match(array('get', 'post'), 'iluvcricket/login', array('as' => 'iluvcricketLogin', 'uses' => 'AdminController@login'));
Route::get('iluvcricket/logout', array('as' => 'iluvcricketLogout', 'uses' => 'AdminController@logout'));
Route::group(array('before' => 'iluvcricketAuth'), function() {
    //Admin home
    Route::get('iluvcricket', array('as' => 'iluvcricket', 'uses' => 'AdminController@index'));

    //Item management
    Route::get('iluvcricket/@@plural@@/view', array('as' => 'iluvcricketView@@plural-pascalCase@@', 'uses' => 'Admin@@plural-pascalCase@@Controller@list@@plural-pascalCase@@'));
    Route::match(array('GET', 'POST'), 'iluvcricket/@@plural@@/create', array('as' => 'iluvcricketCreate@@singular-pascalCase@@', 'uses' => 'Admin@@plural-pascalCase@@Controller@createEdit'));
    Route::match(array('POST'), 'iluvcricket/@@plural@@/delete', array('as' => 'iluvcricketDelete@@singular-pascalCase@@', 'uses' => 'Admin@@plural-pascalCase@@Controller@delete'));

    //Pages
    Route::get('iluvcricket/pages/view', array('as' => 'iluvcricketViewPages', 'uses' => 'AdminPagesController@listPages'));
    Route::match(array('GET', 'POST'), 'iluvcricket/pages/create', array('as' => 'iluvcricketCreatePage', 'uses' => 'AdminPagesController@createEdit'));
    Route::match(array('GET', 'POST'), 'iluvcricket/pages/delete', array('as' => 'iluvcricketDeletePage', 'uses' => 'AdminPagesController@delete'));

    //Config
    Route::match(array('GET', 'POST'), 'iluvcricket/config', array('as' => 'iluvcricketConfig', 'uses' => 'AdminConfigController@index'));
    Route::match(array('GET', 'POST'), 'iluvcricket/config/widgets', array('as' => 'iluvcricketConfigWidgets', 'uses' => 'AdminConfigController@widgets'));
    Route::match(array('GET', 'POST'), 'iluvcricket/config/languages', array('as' => 'iluvcricketConfigLanuages', 'uses' => 'AdminConfigController@languages'));
	Route::match(array('GET', 'POST'), 'iluvcricket/config/@@singular@@', array('as' => 'iluvcricketConfig@@singular-pascalCase@@', 'uses' => 'AdminConfigController@@@singular@@Config'));

    //Change Password
    Route::match(array('GET', 'POST'), 'iluvcricket/change-password', array('as' => 'iluvcricketChangePassword', 'uses' => 'AdminController@changePassword'));

    //Users
    Route::match(array('GET', 'POST'), 'iluvcricket/users/', array('as' => 'iluvcricketUsersHome', 'uses' => 'AdminUsersController@index'));

    //Categories
    Route::match(array('GET', 'POST'), 'iluvcricket/categories', array('as' => 'iluvcricketCategories', 'uses' => 'AdminCategoriesController@view'));
    Route::match(array('GET', 'POST', 'PATCH', 'DELETE'), 'iluvcricket/categories/addEdit', array('as' => 'iluvcricketCategoriesAddEdit', 'uses' => 'AdminCategoriesController@addEdit'));

    //Update
    Route::get('iluvcricket/update', array('as' => 'update', 'uses' => 'UpdateController@index'));
});



//Media manager
Route::group(array(), function()
{
    \Route::get('media', 'W3G\MediaManager\MediaManagerController@showStandalone');
    \Route::any('media/connector', array('as' => 'mediaConnector', 'uses' => 'W3G\MediaManager\MediaManagerController@connector'));
});

Route::get('/login', array('as' => 'login', 'uses' => 'UserController@login'));
Route::get('login/fb', array('as' => 'loginWithFb', 'uses' => 'UserController@loginWithFb'));

Route::get('logout', array('as' => 'logout', 'uses' => 'UserController@logout'));

//404 macro
Response::macro('notFound', function($value = null)
{
    @@singular-pascalCase@@Controller::_load@@plural-pascalCase@@();
    return Response::view('errors.404', array('errorMsg' => strtoupper($value)), 404);
});

App::missing(function($exception)
{
    @@singular-pascalCase@@Controller::_load@@plural-pascalCase@@();
    return Response::view('errors.404', array('errorMsg' => strtoupper($exception->getMessage())), 404);
});
