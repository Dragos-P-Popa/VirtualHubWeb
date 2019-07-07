<?php
include "Charts.php";
include "Airport.php";
include "Weather.php";
include "Gates.php";
include "Runways.php";
include "Frequencies.php";

class AirportInfo {
	public function getInfoFor( $icao ) {
		$charts = new \Charts();
		$charts = $charts->getChartsForAirport( $icao );

		$airport = new \Airport();
		$airport = $airport->getAirportInformation( $icao );

		$gates = new \Gates();
		$gates = $gates->getGatesInformation( $icao );

		$runways = new \Runways();
		$runways = $runways->getRunwaysInformation( $icao );

		$frequencies = new \Frequencies();
		$frequencies = $frequencies->getFrequenciesInformation( $icao );

		$weather       = new \Weather();
		$weatherresult = [];

		if ( isset( $airport["latitude"] ) ) {
			$weatherresult = $weather->GetWeatherForAirport( $icao );
		}


		$result = array();

		if ( isset( $airport["timezone"] ) ) {
			$airport["localdate"]  = $this->getTimeInZone( $airport["timezone"] );
			$result["airport"]     = $airport;
			$result["charts"]      = $charts;
			$result["weather"]     = $weatherresult;
			$result["runways"]     = $runways;
			$result["gates"]       = $gates;
			$result["frequencies"] = $frequencies;

		} else {
			$result["error"] = "Not found";
		}

		return $result;
	}

	public function getTimeInZone( $zone ) {
		$local    = date_default_timezone_set( $zone );
		$date     = date( 'm-d-y' );
		$time     = date( 'h:i a' );
		$time24   = date( 'H:i' );
		$datefull = date( 'l, F jS, Y | H:i' );

		$result = array(
			"date"     => $date,
			"time"     => $time,
			"time24"   => $time24,
			"datefull" => $datefull
		);

		return $result;
	}

}