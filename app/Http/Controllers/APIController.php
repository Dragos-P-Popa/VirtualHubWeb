<?php

namespace App\Http\Controllers;
include( "app/api/AirportInfo.php" );

use App\Gates;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\CalendarLinks\Link;
use DateTime;

class APIController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {

	}

	public function slackEmailNotification( $app, $platform ) {
		$platform = strtolower( $platform );

		switch ( $platform ) {
			case "ios":
				$platform = "iOS";
				break;

			case "android":
				$platform = "Android";
				break;

			default:
				$platform = "Unknown";
				break;
		}

		$this->slack( "
	We have just received a new support message!\n*App:* $app\n*Platform:* $platform\nView now https://mail.google.com/.
	" );

	}

	public function airportinfo( $icao ) {
		$icao = strtoupper( $icao );

		$info = new \AirportInfo();
		$info = $info->getInfoFor( $icao );

		$info["events"] = [
			[
				"name"           => "Delta Anniversary",
				"description"    => "Delta is turning 2! Join us now!",
				"header_image"   => asset( "storage/app/images/events" ) . "/rh9iugh4i5d" . ".jpg",
				"icao"           => "klax",
				"date_time"      => "2019-06-04 21:00 UTC",
				"gates"          => true,
				"calendar_links" => [],
				"occupied"       => [ 5434, 3456, 8965 ],
				"custom_info"    => [
					[
						"Route",
						"KLAX KKFR FKJIORH JIORTEOI DJKE FERIOH KJFK"
					],
					[
						"Aircraft",
						"All Delta Aircrafts"
					],
					[
						"Contact",
						"Contact @sudafly, @dragos or @dylan on the community, or visit this link https://community.infiniteflight.com/t/oneworld-virtual-around-the-world-with-oneworld-vol-2-lgav-222100zjun19/331764"
					],
				],
			]
		];

		$index = 0;

		foreach ( $info["events"] as $event ) {
			$info["events"][ $index ]["date_time"] = $this->isoformat( $event["date_time"] );

			$index ++;
		}

		DB::update( "UPDATE realtime_data SET total = total + 1 WHERE name = 'total_vh_airportinfo_api_used'" );

		return $info;
	}

	public function airportinfoWebAPI( $icao, $section = "all" ) {
		if ( $this->icaoValidator( $icao ) ) {
			$info = $this->airportinfo( $icao );

			switch ( $section ) {
				case "all":
					return JsonResponse::create( $info );
					break;
				case "charts":
					return JsonResponse::create( $info["charts"] );
					break;
				case "runways":
					return JsonResponse::create( $info["runways"] );
					break;
				case "gates":
					return JsonResponse::create( $info["gates"] );
					break;
				case "weather":
					return JsonResponse::create( $info["weather"] );
					break;
				case "frequencies":
					return JsonResponse::create( $info["frequencies"] );
					break;

				default:
					$error = [ "error" => $section . "is not available." ];

					return JsonResponse::create( $error );
			}
		} else {
			$error = [ "error" => "Invalid ICAO" ];

			return JsonResponse::create( $error );
		}
	}

	public function airportinfoWebAPIgates( $icao ) {
		if ( $this->icaoValidator( $icao ) ) {
			$info = new \Gates();

			return JsonResponse::create( $info->getGatesInformation( $icao ) );
		} else {
			$error = [ "error" => "Invalid ICAO" ];

			return JsonResponse::create( $error );
		}
	}

	public function searchAirport( $query ) {
		$airport = DB::select( "SELECT name, icao, concat_ws(' | ', icao, nullif(trim(iata), '')) as 'app_string' FROM vh_airports WHERE search_string LIKE '%" . $query . "%' ORDER BY CASE WHEN icao LIKE '" . $query . "%' THEN 1 WHEN iata LIKE '%" . $query . "' THEN 3 ELSE 4 END LIMIT 25" );
		$json    = json_encode( $airport );
		$json    = json_decode( $json, true );

		return $json;
	}


	public function viewChart() {
		if ( ! isset( $_POST["url"] ) ) {
			echo "<h1>Your session has been interrupted by your browser.</h1>";
			echo "<p>Try again, disable plugins that may cause this, or use a different browser.</p>";
			echo "<p>You will be redirected back in 5 seconds, or close this tab.</p>";
			echo "<script>
setTimeout( function() {
      window.close();
}, 5000);
</script>";
			exit();
		}

		$url  = $_POST["url"];
		$name = $_POST["name"];

		$file     = urldecode( $url );
		$filename = urldecode( $name );

		header( 'Content-type: application/pdf' );
		header( 'Content-Disposition: inline; filename="' . $filename . '"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Accept-Ranges: bytes' );

		readfile( $file );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index( $request, $data ) {


		return view( 'home', $data );
	}

	public function timezone( $zone = null ) {
		if ( isset( $zone ) ) {

		} else {

		}

		$info = new \AirportInfo();
		$time = $info->getTimeInZone( $_POST["timezone"] );

		return $time["datefull"];
	}

	public function isoformat( $datetime ) {
		return date_format( date_create( $datetime ), 'c' );
	}

	public function icaoValidator( $icao ) {
		$isValid = false;

		if ( strlen( $icao ) === 4 ) {
			$isValid = true;
		}

		return $isValid;
	}

	private function slack( $message ) {
		define( 'SLACK_WEBHOOK', 'https://hooks.slack.com/services/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' );

		$message = array( 'payload' => json_encode( array( 'text' => $message ) ) );
		// Use curl to send your message
		$c = curl_init( SLACK_WEBHOOK );
		curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_POST, true );
		curl_setopt( $c, CURLOPT_POSTFIELDS, $message );
		curl_exec( $c );
		curl_close( $c );
	}
}
