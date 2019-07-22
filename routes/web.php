<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------|
| Web Routes                                                               |
|--------------------------------------------------------------------------|
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//VirtualHub Net
Route::get('/', function () {
	if(isset($_COOKIE['vhw_redirect'])) {
		if ($_COOKIE['vhw_redirect'] !== "null") {
			return Redirect::to('/view/' . $_COOKIE['vhw_redirect']);
		}
	}

	return view( 'vhweb.home' );
});


Route::get('/view/{icao}', 'VirtualHubWeb@airport');

Route::get('/view/{icao}/weather', 'VirtualHubWeb@airport');

Route::get('/view/{icao}/charts', 'VirtualHubWeb@airport');

Route::get('/view/{icao}/events', 'VirtualHubWeb@airport');

Route::get('/view/{icao}/runways', 'VirtualHubWeb@airport');

Route::get('/view/{icao}/gates', 'VirtualHubWeb@airport');

Route::get('/events/{icao}/new', 'VirtualHubWeb@newEventPage')->middleware('auth');


//----------------------------------------------------|
//General
Route::get('/terms', function () {
	return view( 'general.terms' );
});

Route::get('/account', function () {
	return view( 'general.terms' );
});

Route::get('/unsupported/', function () {
	return view( 'general.unsupported' );
});


//----------------------------------------------------|
//Dashboard
Route::get('/dashboard', 'DashboardController@index')->middleware('auth');

Route::get('/dashboard/user/all', 'DashboardController@allUsers')->middleware('auth');

Route::get('/dashboard/user/{id}/block', 'DashboardController@block')->middleware('auth');

Route::get('/dashboard/user/{id}/unblock', 'DashboardController@unBlock')->middleware('auth');

Route::get('/dashboard/updatedata', 'DashboardController@updateDataSet')->middleware('auth');


//----------------------------------------------------|
//Api
//CSRF Token required for post

Route::post('/api/timezone', 'APIController@timezone');

Route::get('/api/airportinfo/{icao}/{section?}', 'APIController@airportinfoWebAPI');

Route::get('/api/gates/{icao}', 'APIController@airportinfoWebAPIgates');

Route::get('/api/search/{query?}', 'APIController@searchAirport');

Route::get('/api/slacknotification/email/{app}/{platform}', 'APIController@slackEmailNotification');

Route::post('/view/chart', 'APIController@viewChart');

Route::get('/api/sitemap/generate', 'SitemapController@generate');

Route::get('/api/sitemap/allairports', 'SitemapController@all_ap_page');

Route::post('/api/events/new', 'VirtualHubWeb@addNewEvent')->middleware('auth');

Route::post('/api/events/join', 'VirtualHubWeb@joinEvent')->middleware('auth');

Route::post('/api/events/gates', 'VirtualHubWeb@occupiedGates')->middleware('auth');

//----------------------------------------------------|














Route::get('/airport/{icao}', 'AirportController@index')->middleware('auth');

Route::get('/airport', 'AirportController@index')->middleware('auth');

Route::post('/chart/rename', 'ChartController@rename')->middleware('auth');

Route::post('/chart/delete', 'ChartController@delete')->middleware('auth');

Auth::routes();