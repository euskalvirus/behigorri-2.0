<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
    //Route::resource('api/user', 'Api\UserController');
    //Route::resource('api/group', 'GroupController');
    //Route::resource('api/data', 'Api\SensitiveDataController');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'IndexController@index');

    Route::get('admin/user',['as' => 'adminUser', 'uses' => 'Admin\UserAdministrationController@userAdministration'] );
    Route::get('admin/user/edit/{id}', 'Admin\UserAdministrationController@editUser');
    Route::get('admin/user/view/{id}', 'Admin\UserAdministrationController@viewUser');
    Route::get('admin/user/new', 'Admin\UserAdministrationController@newUser');
    Route::post('admin/user/new/register', ['as' => 'doRegistration', 'uses' => 'Admin\UserAdministrationController@register']);
    Route::get('admin/user/delete/{id}', 'Admin\UserAdministrationController@userDelete');
    Route::post('admin/user/update', ['as' => 'updateUser', 'uses' => 'Admin\UserAdministrationController@userUpdate']);
    Route::post('admin/user/updatePassword', ['as' => 'updateUserPassword', 'uses' => 'Admin\UserAdministrationController@userPasswordUpdate']);
    Route::post('admin/user/search', 'Admin\UserAdministrationController@userSearch');

    Route::get('edit/profile', 'Admin\UserAdministrationController@editProfile');

    Route::get('admin/generateSalt', 'Admin\UserAdministrationController@generateSalt');



    Route::get('admin/group', ['as' => 'adminGroup', 'uses' => 'Admin\GroupAdministrationController@groupAdministration']);
    Route::get('admin/group/new', 'Admin\GroupAdministrationController@newGroup');
    Route::post('admin/group/save', ['as'=> 'saveGroup', 'uses' => 'Admin\GroupAdministrationController@groupSave']);
    Route::get('admin/group/delete/{id}', 'Admin\GroupAdministrationController@groupDelete');
    Route::get('admin/group/edit/{id}', 'Admin\GroupAdministrationController@groupEdit');
    Route::post('admin/group/update', 'Admin\GroupAdministrationController@groupUpdate');
    Route::get('admin/group/view/{id}', 'Admin\GroupAdministrationController@groupView');
    Route::post('admin/group/search', 'Admin\GroupAdministrationController@groupSearch');



    Route::get('data/edit/{id}', 'data\SensitiveDataController@sensitiveDataEdit');
    Route::get('data/new', 'data\SensitiveDataController@newSensitiveData');
    Route::get('data/newFile', 'data\SensitiveDataController@newSensitiveDataFile');
    Route::post('data/save', 'data\SensitiveDataController@sensitiveDataSave');
    Route::post('data/saveFile', 'data\SensitiveDataController@sensitiveDataFileSave');
    Route::post('data/update', 'data\SensitiveDataController@sensitiveDataUpdate');
    Route::get('data/delete/{id}', 'data\SensitiveDataController@sensitiveDataDelete');
    Route::get('data/view/{id}', 'data\SensitiveDataController@sensitiveDataView');
    Route::post('data/search', 'data\SensitiveDataController@sensitiveDataSearch');
    Route::get('data/searchTag/{name}', 'data\SensitiveDataController@sensitiveDataSearchByTag');



    Route::get('auth/logout',['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

    Route::get('{locale}/', function ($locale) {
        App::setLocale($locale);
        return redirect('/');

    //
});



});
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login',['as' => 'postLogin', 'uses' => 'Auth\AuthController@postLogin']);


//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('admin/user/new/register', ['as' => 'doRegistration', 'uses' => 'Admin\UserAdministrationController@register']);



Route::get('activation/{code}', 'Admin\UserAdministrationController@activeUser');
//missing routes, redirect to '/'
Route::any('{catchall}', function() {
  return Redirect::to('/');
})->where('catchall', '.*');
