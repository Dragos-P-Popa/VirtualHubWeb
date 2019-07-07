<?php

namespace App\Http\Controllers;
include( "app/api/Charts.php" );

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ChartController extends Controller {
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
	public function index( $name ) {
		$icao = substr( $name, 0, 4 );


		return view( 'chart', array(
			"icao" => $icao,
			"name" => $this->propername( $name, $icao )
		) );
	}

	public function upload() {


	}

	public function rename() {
		$old = $_POST["oldname"];
		$new = $_POST["newname"];

		$icao = substr( $old, 0, 4 );
		$ext  = pathinfo( $old, PATHINFO_EXTENSION );

		$new = $icao . " " . $new;

		$new = str_replace( " ", "+", $new );
		$new = strtoupper( $new ) . "." . $ext;

		$exist = Storage::exists( "charts/" . $old );

		sleep( 1 );

		if ( $exist ) {
			$r = Storage::move( "charts/" . $old, "charts/" . $new );

			echo json_encode( [
				"renamed"    => $r,
				"newname"    => $new,
				"newappname" => $this->propername( $new, $icao ),
				"url"        => asset( "storage/app/charts" ) . "/" . $new
			] );
		} else {
			echo "refresh";
		}
	}

	public function delete() {
		$chart = $_POST["chart"];
		$exist = Storage::exists( "charts/" . $chart );

		if ( $exist ) {
			Storage::delete( "charts/" . $chart );
			echo "deleted";
		} else {
			echo "refresh";
		}
	}

	private function propername( $n, $icao ) {
		$name = urldecode( $n );
		$name = str_replace( ".pdf", "", $name );
		$name = str_replace( ".jpg", "", $name );
		$name = str_replace( ".jpeg", "", $name );
		$name = str_replace( $icao . " ", "", $name );

		return $name;
	}
}
