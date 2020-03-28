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

Route::get('/preview/blocked', function() {
    return view('preview-blocked');
})->name('preview.blocked');

Auth::routes(['register' => false]);

Route::prefix('/')->middleware('auth')->group(function () {

    // Estimates
    Route::resource('estimates', 'EstimateController');
    Route::get('/estimates/{estimate}/duplicate', 'EstimateController@duplicate')->name('estimates.duplicate');

    // Estimate Sections
    Route::apiResource('estimates/{estimate}/sections', 'SectionController');

    Route::middleware('block.on.preview')->group(function() {
        
        // Settings
        Route::get('/settings', 'SettingController@edit')->name('settings.edit');
        Route::put('/settings', 'SettingController@update')->name('settings.update');
        Route::post('/settings/logo', 'SettingController@storeLogo')->name('settings.image.store');
        Route::delete('/settings/logo', 'SettingController@removeLogo')->name('settings.image.remove');

        // Users
        Route::resource('users', 'UserController')->middleware('block.on.preview');

    });

});

Route::get('estimates/{estimate}/data', 'EstimateViewerController@getData');
Route::get('estimates/{estimate}/view', 'EstimateViewerController@show')->name('estimates.view');
Route::post('estimates/{estimate}/share', 'EstimateViewerController@share')->name('estimates.share');
