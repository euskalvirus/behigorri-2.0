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
    Route::resource('api/user', 'Api\UserController');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'IndexController@index');
    Route::resource('api/group', 'GroupController');
    Route::resource('api/data', 'Api\SensitiveDataController');
    
    Route::get('admin/user',['as' => 'adminUser', 'uses' => 'Admin\UserAdministrationController@userAdministration'] );
    Route::get('admin/user/edit/{id}', 'Admin\UserAdministrationController@userEdit');
    Route::get('admin/user/new', 'Admin\UserAdministrationController@newUser');
    Route::post('admin/user/new/register', ['as' => 'doRegistration', 'uses' => 'Admin\UserAdministrationController@register']);
    
    Route::get('admin/group', ['as' => 'adminGroup', 'uses' => 'Admin\GroupAdministrationController@groupAdministration']);
    
    Route::get('data/edit/{id}', 'data\SensitiveDataController@sensitiveDataEdit');
    Route::get('data/new', 'data\SensitiveDataController@newSensitiveData');
    Route::post('data/save', 'data\SensitiveDataController@sensitiveDataSave');
    Route::post('data/update', 'data\SensitiveDataController@sensitiveDataUpdate');
    Route::get('data/delete/{id}', 'data\SensitiveDataController@sensitiveDataDelete');
    
});
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login',['as' => 'postLogin', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout',['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

//Route::get('auth/register', 'Auth\AuthController@getRegister');

Route::get('auth/logout',['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);



