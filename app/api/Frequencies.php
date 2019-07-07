<?php
use Illuminate\Support\Facades\DB;

class Frequencies {
	public function getFrequenciesInformation($icao) {
		$frequencies = DB::select("SELECT * FROM vh_frequencies WHERE airport = '" . $icao . "' ORDER BY name ASC");
		$json = json_encode($frequencies);
		$json = json_decode($json, true);

		$current_index = 0;

		foreach ($json as $atc) {
			$json[$current_index]["app_string"] = "Frequency: " . $atc["frequency"];

			$current_index++;
		}

		return $json;
	}

}