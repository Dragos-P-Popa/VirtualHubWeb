<?php

namespace App\Http\Controllers;
include( "app/api/AirportInfo.php" );

use App\Flightplans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AirportController extends Controller {
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
	public function index( $icao ) {
		$icao = strtoupper( $icao );

		$info = new \AirportInfo();
		$info = $info->getInfoFor($icao);

		return view( 'airport', array(
			"info" => $info
		) );
	}
}
