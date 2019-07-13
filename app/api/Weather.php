<?php

class Weather {

	private $weather_condition = [ "Good" => 0, "Moderate" => 0, "Bad" => 0 ];

	function GetWeatherForAirport( $icao ) {
		$ctx = stream_context_create( array(
			'http' =>
				array(
					'timeout' => 1.5,  //1200 Seconds is 20 Minutes
				)
		) );

		$fileContents = @file_get_contents( "https://aviationweather.gov/adds/dataserver_current/httpparam?requestType=retrieve&dataSource=metars&hoursBeforeNow=2&stationString=" . $icao . "&format=xml", false, $ctx );

		if ( !$fileContents ) {
			return array();
		}

		$fileContents = str_replace( array( "\n", "\r", "\t" ), '', $fileContents );
		$fileContents = trim( str_replace( '"', "'", $fileContents ) );
		$simpleXml    = simplexml_load_string( $fileContents );
		$json         = json_encode( $simpleXml );
		$array        = json_decode( $json, true );

		if ( $array["data"]["@attributes"]["num_results"] == 0 ) {
			return array();
		} else {
			dd($array);
			return $this->parseWeather( $array["data"]["METAR"][0] );
		}
	}

	function parseWeather( $response ) {
		$weatherMinifiedJson = [];

		$weatherMinifiedJson["temperature"]["value_c"]   = round( $response["temp_c"] );
		$weatherMinifiedJson["temperature"]["value_f"]   = round( $response["temp_c"] * 9 / 5 + 32 );
		$weatherMinifiedJson["temperature"]["condition"] = "Good";

		$weatherMinifiedJson["visibility"]["value_km"]  = round( $response["visibility_statute_mi"] * 1.609344 );
		$weatherMinifiedJson["visibility"]["value_m"]   = round( $response["visibility_statute_mi"], 1 );
		$weatherMinifiedJson["visibility"]["condition"] = "Good";

		$weatherMinifiedJson["pressure"]["value_hg"]  = round( $response["altim_in_hg"], 1 );
		$weatherMinifiedJson["pressure"]["value_hpa"] = round( $response["altim_in_hg"] / 0.02953 );
		$weatherMinifiedJson["pressure"]["condition"] = "Good";

		$weatherMinifiedJson["wind"]["value_kts"]     = round( $response["wind_speed_kt"] );
		$weatherMinifiedJson["wind"]["value_kmh"]     = round( $response["wind_speed_kt"] * 1.85200 );
		$weatherMinifiedJson["wind"]["value_heading"] = round( $response["wind_dir_degrees"] );
		$weatherMinifiedJson["wind"]["condition"]     = "Bad";

		$weatherMinifiedJson["station"] = $response["station_id"];
		$weatherMinifiedJson["metar"]   = $response["raw_text"];

		$visVal  = $weatherMinifiedJson["visibility"]["value_km"];
		$tempVal = $weatherMinifiedJson["temperature"]["value_c"];
		$windVal = $weatherMinifiedJson["wind"]["value_kts"];

		switch ( $visVal ) {
			case $visVal >= 7:
				$weatherMinifiedJson["visibility"]["condition"] = "Good";
				$this->weather_condition["Good"] ++;
				break;
			case $visVal >= 3 && $visVal <= 6:
				$weatherMinifiedJson["visibility"]["condition"] = "Moderate";
				$this->weather_condition["Moderate"] ++;
				break;
			case $visVal <= 2:
				$weatherMinifiedJson["visibility"]["condition"] = "Bad";
				$this->weather_condition["Bad"] ++;
				break;
		}

		switch ( $tempVal ) {
			case $tempVal > - 40 && $tempVal < 52:
				$weatherMinifiedJson["temperature"]["condition"] = "Good";
				$this->weather_condition["Good"] ++;
				break;
			default:
				$weatherMinifiedJson["temperature"]["condition"] = "Bad";
				$this->weather_condition["Bad"] ++;
				break;
		}

		switch ( $windVal ) {
			case $windVal >= 20:
				$weatherMinifiedJson["wind"]["condition"] = "Bad";
				$this->weather_condition["Bad"] ++;
				break;
			case $windVal >= 11 && $windVal <= 19:
				$weatherMinifiedJson["wind"]["condition"] = "Moderate";
				$this->weather_condition["Moderate"] ++;
				break;
			case $windVal <= 10:
				$weatherMinifiedJson["wind"]["condition"] = "Good";
				$this->weather_condition["Good"] ++;
				break;
		}

		$overall_condition = array_search( max( $this->weather_condition ), $this->weather_condition );

		$weatherMinifiedJson["wind"]["value_app"]        = $weatherMinifiedJson["wind"]["value_heading"] . "° at " . $weatherMinifiedJson["wind"]["value_kts"] . " KTS (" . $weatherMinifiedJson["wind"]["value_kmh"] . " Km/H)";
		$weatherMinifiedJson["pressure"]["value_app"]    = $weatherMinifiedJson["pressure"]["value_hg"] . " inHg (" . $weatherMinifiedJson["pressure"]["value_hpa"] . " hPa)";
		$weatherMinifiedJson["visibility"]["value_app"]  = $weatherMinifiedJson["visibility"]["value_km"] . " KM (" . $weatherMinifiedJson["visibility"]["value_m"] . " Mi)";
		$weatherMinifiedJson["temperature"]["value_app"] = $weatherMinifiedJson["temperature"]["value_c"] . " °C (" . $weatherMinifiedJson["temperature"]["value_f"] . " °F)";

		$weatherMinifiedJson = [
			[
				"Temperature",
				$weatherMinifiedJson["temperature"]["value_app"],
				$weatherMinifiedJson["temperature"]["condition"]
			],
			[
				"Visibility",
				$weatherMinifiedJson["visibility"]["value_app"],
				$weatherMinifiedJson["visibility"]["condition"]
			],
			[
				"Pressure",
				$weatherMinifiedJson["pressure"]["value_app"],
				$weatherMinifiedJson["pressure"]["condition"]
			],
			[
				"Wind",
				$weatherMinifiedJson["wind"]["value_app"],
				$weatherMinifiedJson["wind"]["condition"]
			],
			[
				"Metar",
				$weatherMinifiedJson["metar"],
				$overall_condition
			],
			[
				"Station",
				$weatherMinifiedJson["station"],
				'Unknown'
			]
		];

		return $weatherMinifiedJson;
	}

}