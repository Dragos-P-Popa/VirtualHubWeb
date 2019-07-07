<?php
use Illuminate\Support\Facades\DB;

class Airport {
	public function getAirportInformation($icao) {
		$airport = DB::select("SELECT * FROM vh_airports WHERE icao = '" . $icao . "'");
		$json = json_encode($airport);
		$json = json_decode($json, true);

		if (count($json) != 0) {
			$json = $json[0];
		}

		return $json;
	}

}