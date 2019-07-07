<?php
use Illuminate\Support\Facades\DB;

class Gates {
	public function getGatesInformation($icao) {
		$airport = DB::select("SELECT * FROM vh_gates WHERE airport = '" . $icao . "' ORDER BY size ASC");
		$json = json_encode($airport);
		$json = json_decode($json, true);

		$current_index = 0;

		foreach ($json as $gate) {
			$json[$current_index]["app_string"] = "Class: " . $gate["size"] . "\nAircrafts: " .  $gate["aircrafts"];
			$json[$current_index]["name"] = $gate["type"] . " " .  $gate["name"];

			$current_index++;
		}

		return $json;
	}

}