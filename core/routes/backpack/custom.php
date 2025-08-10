<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::crud('office-file', 'OfficeFileCrudController');
    Route::post("office-file/{id}/inspection/no-adapt","OfficeFileCrudController@inspectionNoAdapt")->name("office-file.submit-no-adapt");
    Route::post('office-file/{id}/submit-inspection', 'OfficeFileCrudController@submitInspection')->name("office-file.submit-inspection");
    Route::post('office-file/{id}/inspections', 'OfficeFileCrudController@submitInspection')->name("office-file.inspections");
    Route::crud('inspection-log-employee', 'InspectionLogEmployeeCrudController');
    Route::crud('inspection-log', 'InspectionLogCrudController');
	Route::get('inspection-log/{id}/print', 'InspectionLogCrudController@print');
    Route::crud('inspection-log-notification', 'InspectionLogNotificationCrudController');
    // Route::crud('user', 'UserCrudController');
    Route::crud('complaint', 'ComplaintCrudController');
	Route::get('report', 'ReportController@report');
	
	Route::post('report', 'ReportController@post_report');
	Route::post('report/export', 'ReportController@excelExport');
}); // this should be the absolute last line of this file