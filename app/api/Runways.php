<?php
use Illuminate\Support\Facades\DB;

class Runways {
	public function getRunwaysInformation($icao) {
		$runways = DB::select("SELECT * FROM vh_runways WHERE airport = '" . $icao . "' ORDER BY length_ft ASC");
		$json = json_encode($runways);
		$json = json_decode($json, true);

		$current_index = 0;

		foreach ($json as $runway) {

			$app_dimension = "Length: " . $runway["length_ft"] . " FT (" . $runway["length_km"] . " KM)";
			$app_idents    = $runway["ident1"] . " - " . $runway["ident2"];

			$json[$current_index]["app_dimension"] = $app_dimension;
			$json[$current_index]["app_idents"] = $app_idents;

			$current_index++;
		}

		return $json;
	}

}