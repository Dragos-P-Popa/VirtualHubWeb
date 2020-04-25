<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Events;
use App\EventsGates;
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

	public function airport( $icao ) {
        $info = Airport::FullInfo($icao);
        return view( 'vhweb.airport', compact('info'));
	}

	public function newEventPage( $icao ) {
        $info = Airport::Info($icao);
        return view( 'vhweb.newEvent', compact('info'));
	}

	public function addNewEvent() {
		$event = new Events();
		$event->title = $_POST["event_name"];
		$event->description = $_POST["event_description"];
		$event->start = $_POST["event_date_time_start"];
		$event->end = $_POST["event_date_time_end"];
		$event->route = $_POST["event_route"];
		$event->sections = $_POST["event_sections"];
		$event->airport = $_POST["event_airport"];
		$event->server = $_POST['event_server'];
		$event->user_id = Auth::user()->id;
		$event->save();

		return redirect( 'view/' . $event->airport . '/events' );
	}

	public function joinEvent() {
		$user_id     = Auth::user()->id;
		$event_id = $_POST["event_id"];
		$gate_id = $_POST["gate_id"];

		$response = EventsGates::Join($user_id, $event_id, $gate_id);

		return JsonResponse::create($response);
	}

	public function removeEvent( $id ) {
	    $id = (int) $id;

        $event = Events::ByID($id);
        $airport = $event[0]->airport;

        DB::delete("DELETE FROM vh_events_gates WHERE event_id = " . $id . ";");
        DB::delete("DELETE FROM vh_events WHERE id = " . $id . ";");

        return redirect( 'view/' . $airport );
    }

	public function occupiedGates() {
		$event_id = $_POST["event_id"];

		$get_gate_occupied = DB::select("SELECT * FROM vh_events_gates eg, vh_gates g WHERE event_id = " . $event_id . " AND g.uid = eg.gate_id;");

		return JsonResponse::create(["success" => true, "gates" => $get_gate_occupied]);
	}
}
