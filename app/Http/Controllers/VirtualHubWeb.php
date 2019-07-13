<?php

namespace App\Http\Controllers;

use App\Flightplans;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;


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

	public function addNewEvent() {
		$name        = $_POST["event_name"];
		$description = $_POST["event_description"];
		$start       = $_POST["event_date_time_start"];
		$end         = $_POST["event_date_time_end"];
		$route       = $_POST["event_route"];
		$sections    = $_POST["event_sections"];
		$airport    = $_POST["event_airport"];
		$user_id     = Auth::user()->id;

		$query = "INSERT INTO vh_events SET airport = '" . $airport . "', title = '" . $name . "', description = '" . $description . "', start = '" . $start . "', end = '" . $end . "', route = '" . $route . "', sections = '" . $sections . "', occupied_gates = '[]', user_id = '" . $user_id . "'";
		DB::insert($query);

		return redirect('view/' . $airport . '/events');
	}
}
