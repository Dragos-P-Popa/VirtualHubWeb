<?php

namespace App\Http\Controllers;

use App\Flightplans;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class VirtualHubWeb extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function airport( $icao ) {
		$info = new APIController();
		$info = $info->airportinfo($icao);

		return view( 'vhweb.airport', array(
			"info" => $info
		) );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {

		$airports = file_get_contents( asset( "storage/app/vhw/airports.json" ) );

		return view( 'vhweb.home', array(
			"geo_airports" => str_replace("", "", $airports)
		) );
	}
}
