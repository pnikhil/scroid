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

Route::get('lists', array('as' => 'lists', 'uses' => 'ListController@index'));
Route::get('lists/popular', array('as' => 'popularLists', 'uses' => 'ListController@popular'));
Route::get('lists/{nameString}/{listId}/{item?}', array('as' => 'viewList', 'uses' => 'ListController@viewList'));
Route::post('lists/{listId}/stats', array('as' => 'recordListStats', 'uses' => 'ListController@recordListStats'));
Route::get('tag/{slug}', array('as' => 'viewListsOfTag', 'uses' => 'ListController@viewListsOfTag'));
Route::get('search', array('as' => 'search', 'uses' => 'ListController@search'));

Route::get('users/confirm-email/{code}', array('as' => 'confirmEmail', 'uses' => 'UserController@confirmEmail'));
//User profile
Route::get('users/{id}/{nameString}', array('as' => 'userProfile', 'uses' => 'UserController@profile'));

Route::controller('users', 'UserController');
Route::controller('password', 'RemindersController');

Route::get('login', array('as' => 'login', 'uses' => 'UserController@getLogin'));
Route::get('login/fb', array('as' => 'loginWithFb', 'uses' => 'UserController@loginWithFb'));

Route::get('logout', array('as' => 'logout', 'uses' => 'UserController@logout'));

Route::get('sitemap.xml', array('as' => 'sitemap', 'uses'   =>  'SitemapController@index'));
Route::get('feed', array('as' => 'rssFeed', 'uses'   =>  'RssFeedController@index'));

Route::group(array('before' => 'auth'), function() {
    Route::match(array('get', 'post'), 'create', array('as' => 'createList', 'uses' => 'ListController@createEdit'));
    Route::match(array('get', 'post'), 'edit', array('as' => 'editList', 'uses' => 'ListController@createEdit'));
    Route::post('create/publish', array('as' => 'publishList', 'uses' => 'ListController@publishList'));
    Route::post('create/upload-image', array('as' => 'listUploadImage', 'uses' => 'ListController@uploadImage'));
    Route::get('preview-list', array('as' => 'previewList', 'uses' => 'ListController@previewList'));
    //My profile
    Route::get('me', array('as' => 'myProfile', 'uses' => 'UserController@myProfile'));
    Route::match(array('get', 'post'), 'me/settings', array('as' => 'myProfileSettings', 'uses' => 'UserController@myProfileSettings'));
});

Route::get('pages/{nameString}.html', array('as' => 'viewPage', 'uses' => 'PageController@viewPage'));
Route::get('category/{slug}', array('as' => 'category', 'uses' => 'ListController@category'));

$iluvcricket = Session::get('iluvcricket');
View::share('loggedInAdmin', $iluvcricket);

App::singleton('loggedInAdmin', function() use($iluvcricket) {
    return $iluvcricket;
});

Route::filter('iluvcricketAuth', function()
{
    $iluvcricket = Session::get('iluvcricket');

    //Populate view with common data for iluvcrickets
    AdminBaseController::populateView();

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
    Route::get('iluvcricket/lists/view', array('as' => 'iluvcricketViewLists', 'uses' => 'AdminListsController@listLists'));
    Route::match(array('POST'), 'iluvcricket/lists/delete', array('as' => 'iluvcricketDeleteList', 'uses' => 'AdminListsController@delete'));
    Route::post('iluvcricket/lists/approve-list', array('as' => 'approveList', 'uses' => 'AdminListsController@approveList'));
    Route::post('iluvcricket/lists/disapprove-list', array('as' => 'disapproveList', 'uses' => 'AdminListsController@disapproveList'));

    //Pages
    Route::get('iluvcricket/pages/view', array('as' => 'iluvcricketViewPages', 'uses' => 'AdminPagesController@listPages'));
    Route::match(array('GET', 'POST'), 'iluvcricket/pages/create', array('as' => 'iluvcricketCreatePage', 'uses' => 'AdminPagesController@createEdit'));
    Route::match(array('GET', 'POST'), 'iluvcricket/pages/delete', array('as' => 'iluvcricketDeletePage', 'uses' => 'AdminPagesController@delete'));

    //Config
    Route::match(array('GET', 'POST'), 'iluvcricket/config', array('as' => 'iluvcricketConfig', 'uses' => 'AdminConfigController@index'));
    Route::match(array('GET', 'POST'), 'iluvcricket/config/widgets', array('as' => 'iluvcricketConfigWidgets', 'uses' => 'AdminConfigController@widgets'));
    Route::match(array('GET', 'POST'), 'iluvcricket/config/languages', array('as' => 'iluvcricketConfigLanuages', 'uses' => 'AdminConfigController@languages'));
	Route::match(array('GET', 'POST'), 'iluvcricket/config/list', array('as' => 'iluvcricketConfigList', 'uses' => 'AdminConfigController@listConfig'));
	Route::match(array('GET', 'POST'), 'iluvcricket/config/email', array('as' => 'iluvcricketConfigEmail', 'uses' => 'AdminConfigController@emailConfig'));

    //Preview OG image
    Route::match(array('GET', 'POST'), 'iluvcricket/config/preview-og-image', array('as' => 'iluvcricketPreviewOgImage', 'uses' => 'AdminConfigController@previewOgImage'));

    //Change Password
    Route::match(array('GET', 'POST'), 'iluvcricket/change-password', array('as' => 'iluvcricketChangePassword', 'uses' => 'AdminController@changePassword'));

    //Users
    Route::controller('iluvcricket/users', 'AdminUsersController');
    Route::match(array('GET', 'POST'), 'iluvcricket/users/', array('as' => 'iluvcricketUsersHome', 'uses' => 'AdminUsersController@index'));

    //Categories
    Route::match(array('GET', 'POST'), 'iluvcricket/categories', array('as' => 'iluvcricketCategories', 'uses' => 'AdminCategoriesController@view'));
    Route::match(array('GET', 'POST', 'PATCH', 'DELETE'), 'iluvcricket/categories/addEdit', array('as' => 'iluvcricketCategoriesAddEdit', 'uses' => 'AdminCategoriesController@addEdit'));

    //Update
    Route::get('iluvcricket/update', array('as' => 'update', 'uses' => 'UpdateController@index'));

    //Sitemap
    Route::controller('sitemap', 'AdminSitemapController');
});



//Media manager
Route::group(array(), function()
{
    \Route::get('media', 'W3G\MediaManager\MediaManagerController@showStandalone');
    \Route::any('media/connector', array('as' => 'mediaConnector', 'uses' => 'W3G\MediaManager\MediaManagerController@connector'));
});

//404 macro
Response::macro('notFound', function($value = null)
{
    ListController::_loadLists();
    return Response::view('errors.404', array('errorMsg' => strtoupper($value)), 404);
});

App::missing(function($exception)
{
    ListController::_loadLists();
    return Response::view('errors.404', array('errorMsg' => strtoupper($exception->getMessage())), 404);
});
