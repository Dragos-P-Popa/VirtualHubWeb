<?php

namespace App\Http\Controllers;

use App\Flightplans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		$routesQuery    = 'select count(*) AS "total" from flightplan_routes';
		$routesReported = 'SELECT CONCAT(fa.icao, " - ", fa2.icao, " | ", fr.reportedTimes, " Times") AS "data", fr.id AS "id" FROM flightplan_routes fr, flightplan_airports fa, flightplan_airports fa2 WHERE fr.departure = fa.id AND fr.arrival = fa2.id AND fr.reportedTimes != 0;';
		$routesReview   = 'SELECT CONCAT(fa.icao, " - ", fa2.icao) AS "data", fr.id AS "id" FROM flightplan_routes fr, flightplan_airports fa, flightplan_airports fa2 WHERE fr.departure = fa.id AND fr.arrival = fa2.id AND fr.status = "Waiting";';

		$totalRoutes = DB::select( $routesQuery );
		$totalRoutes = json_decode( json_encode( $totalRoutes, true ), true );

		$totalPublicRoutes = DB::select( $routesQuery . ' WHERE public = true' );
		$totalPublicRoutes = json_decode( json_encode( $totalPublicRoutes, true ), true );

		$totalPrivateRoutes = DB::select( $routesQuery . ' WHERE public = false' );
		$totalPrivateRoutes = json_decode( json_encode( $totalPrivateRoutes, true ), true );

		$routesReported = DB::select( $routesReported );
		$routesReported = json_decode( json_encode( $routesReported, true ), true );

		$routesReview = DB::select( $routesReview );
		$routesReview = json_decode( json_encode( $routesReview, true ), true );

		if ( count( $routesReported ) == 1 ) {
			$routesReported = array(
				array(
					"data" => $routesReported[0]["data"],
					"id"   => $routesReported[0]["id"]
				)
			);
		}

		if ( count( $routesReview ) == 1 ) {
			$routesReview = array(
				array(
					"data" => $routesReview[0]["data"],
					"id"   => $routesReview[0]["id"]
				)
			);
		}

		$data = array(
			"total_flightplans"         => $totalRoutes[0]['total'],
			"total_public_flightplans"  => $totalPublicRoutes[0]['total'],
			"total_private_flightplans" => $totalPrivateRoutes[0]['total'],
			"reported_routes"           => $routesReported,
			"reported_routes_count"     => count( $routesReported ),
			"review_routes"             => $routesReview,
			"review_routes_count"       => count( $routesReview )
		);


		return view( 'home', $data );
	}
}
