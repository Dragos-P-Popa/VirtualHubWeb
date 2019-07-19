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
		$info = $info->airportinfo( $icao );

		$info["airport"]["bounds"] = $this->getBoundingBox($info["airport"]["latitude"], $info["airport"]["longitude"], 3);

		return view( 'vhweb.airport', array(
			"info" => $info
		) );
	}

	public function newEventPage( $icao ) {
		include "app/api/Airport.php";

		$info = new \Airport();
		$info = $info->getAirportInformation( $icao );

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
		$airport     = $_POST["event_airport"];
		$user_id     = Auth::user()->id;

		$query = "INSERT INTO vh_events SET airport = '" . $airport . "', title = '" . $name . "', description = '" . $description . "', start = '" . $start . "', end = '" . $end . "', route = '" . $route . "', sections = '" . $sections . "', user_id = '" . $user_id . "'";
		DB::insert( $query );

		return redirect( 'view/' . $airport . '/events' );
	}

	public function joinEvent() {
		$user_id     = Auth::user()->id;
		$event_id = $_POST["event_id"];
		$gate_id = $_POST["gate_id"];

		$get_gate_occupied = DB::select("SELECT * FROM vh_events_gates WHERE event_id = " . $event_id . " AND gate_id = '" . $gate_id . "';");

		if (count($get_gate_occupied) != 0) {
			return JsonResponse::create(["success" => false, "reason" => "This gate is occupied."]);
		} else {
			$get_gate_occupied_byuser = DB::select("SELECT * FROM vh_events_gates WHERE event_id = " . $event_id . " AND user_id = '" . $user_id . "';");

			if (count($get_gate_occupied_byuser) != 0) {
				DB::delete("DELETE FROM vh_events_gates WHERE event_id = " . $event_id . " AND user_id = '" . $user_id . "';");
			}

			$query = "INSERT INTO vh_events_gates SET event_id = " . $event_id . ", gate_id = '" . $gate_id . "', user_id = " . $user_id . ";";
			DB::insert( $query );

			return JsonResponse::create(["success" => true]);
		}
	}

	public function occupiedGates() {
		$event_id = $_POST["event_id"];

		$get_gate_occupied = DB::select("SELECT * FROM vh_events_gates eg, vh_gates g WHERE event_id = " . $event_id . " AND g.uid = eg.gate_id;");

		return JsonResponse::create(["success" => true, "gates" => $get_gate_occupied]);
	}

	// Distance is in km, alat and alon are in degrees
	function getBoundingBox($lat_degrees,$lon_degrees,$distance_in_miles) {

		$radius = 3963.1; // of earth in miles

		// bearings - FIX
		$due_north = deg2rad(0);
		$due_south = deg2rad(180);
		$due_east = deg2rad(90);
		$due_west = deg2rad(270);

		// convert latitude and longitude into radians
		$lat_r = deg2rad($lat_degrees);
		$lon_r = deg2rad($lon_degrees);

		// find the northmost, southmost, eastmost and westmost corners $distance_in_miles away
		// original formula from
		// http://www.movable-type.co.uk/scripts/latlong.html

		$northmost  = asin(sin($lat_r) * cos($distance_in_miles/$radius) + cos($lat_r) * sin ($distance_in_miles/$radius) * cos($due_north));
		$southmost  = asin(sin($lat_r) * cos($distance_in_miles/$radius) + cos($lat_r) * sin ($distance_in_miles/$radius) * cos($due_south));

		$eastmost = $lon_r + atan2(sin($due_east)*sin($distance_in_miles/$radius)*cos($lat_r),cos($distance_in_miles/$radius)-sin($lat_r)*sin($lat_r));
		$westmost = $lon_r + atan2(sin($due_west)*sin($distance_in_miles/$radius)*cos($lat_r),cos($distance_in_miles/$radius)-sin($lat_r)*sin($lat_r));


		$northmost = rad2deg($northmost);
		$southmost = rad2deg($southmost);
		$eastmost = rad2deg($eastmost);
		$westmost = rad2deg($westmost);

		// sort the lat and long so that we can use them for a between query
		if ($northmost > $southmost) {
			$lat1 = $southmost;
			$lat2 = $northmost;

		} else {
			$lat1 = $northmost;
			$lat2 = $southmost;
		}


		if ($eastmost > $westmost) {
			$lon1 = $westmost;
			$lon2 = $eastmost;

		} else {
			$lon1 = $eastmost;
			$lon2 = $westmost;
		}

		return array($lat1,$lat2,$lon1,$lon2);
	}
}
