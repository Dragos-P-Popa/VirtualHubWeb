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

	public function newEventPage($icao) {
		include "app/api/Airport.php";

		$info = new \Airport();
		$info = $info->getAirportInformation($icao);

		return view( 'vhweb.newEvent', array(
			"info" => $info
		) );
	}

}
