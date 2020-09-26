<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/upload-file', '\App\Http\Controllers\NewsController@uploadFile');

Route::prefix('/importer')->group(function () {
    Route::get('/test-connection', '\App\Http\Controllers\ImporterController@testElasticsearchConnection');
    Route::get('/index-stats', '\App\Http\Controllers\ImporterController@getElasticsearchIndexStatus');
    Route::get('/index-documents', '\App\Http\Controllers\ImporterController@listElasticsearchIndexDocuments');
    Route::get('/nodes-stats', '\App\Http\Controllers\ImporterController@getElasticsearchNodesStats');
    Route::get('/cluster-stats', '\App\Http\Controllers\ImporterController@getElasticsearchClusterStats');
    Route::get('/delete-index', '\App\Http\Controllers\ImporterController@deleteElasticsearchIndex');
    Route::get('/import-data', '\App\Http\Controllers\ImporterController@importData');
});
