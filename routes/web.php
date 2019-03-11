<?php

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



Route::get('/', 'SearchController@index')->name('search');
Route::post('/', 'SearchController@result');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/services', 'AdminDashboardController@index')->name('admin_dashboard');
Route::get('/edit_service/{service}', 'AdminDashboardController@editService')->name('edit_service');

Route::resource('/datatable/services', 'ServiceDataTableController');
