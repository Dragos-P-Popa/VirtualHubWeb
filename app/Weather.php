<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model {

    static function ForAirport( string $icao ) {
        return self::LoadWeather($icao);
    }

    private static function LoadWeather( string $icao ) {
        $api_url = "https://aviationweather.gov/adds/dataserver_current/httpparam?requestType=retrieve&dataSource=metars&hoursBeforeNow=2&stationString=" . $icao . "&format=xml";
        $result = [];
        $dataAvailable = false;

        $ctx = stream_context_create( array(
            'http' => array(
                'timeout' => 1.5,
            )
        ) );

        $apiResponse = @file_get_contents( $api_url, false, $ctx );

        if($apiResponse) {
            $apiResponse = Helper::XmlToJSON($apiResponse);

            if (isset($apiResponse->attributes->num_results)) {
                if($apiResponse->attributes->num_results != 0) {
                    $dataAvailable = true;
                    $apiResponse = $apiResponse->METAR[0];
                }
            }
        }

        if($dataAvailable) {
            if (isset($apiResponse->temp_c)) {
                $celcius = round($apiResponse->temp_c);
                $farenheid = round($celcius * 9 / 5 + 32);
                $condition = "Bad";

                if($celcius > - 40 && $celcius < 52) {
                    $condition = "Good";
                }

                $result[] = ["Temperature", $celcius . " °C (" . $farenheid . " °F)", $condition];
            }

            if (isset($apiResponse->visibility_statute_mi)) {
                $miles = round($apiResponse->visibility_statute_mi, 1);
                $kilometers = round($miles * 1.609344);
                $condition = "Bad";

                if($kilometers >= 7) {
                    $condition = "Good";
                }

                if($kilometers >= 3 && $kilometers <= 6) {
                    $condition = "Moderate";
                }

                $result[] = ["Visibility", $kilometers . " KM (" . $miles . " Mi)", $condition];
            }

            if (isset($apiResponse->altim_in_hg)) {
                $hg = round( $apiResponse->altim_in_hg, 1 );
                $hpa = round( $hg / 0.02953 );
                $condition = "Good";
                $result[] = ["Pressure", $hg . " inHg (" . $hpa . " hPa)", $condition];
            }

            if (isset($apiResponse->wind_speed_kt)) {
                $kts = round($apiResponse->wind_speed_kt);
                $kmh = round($kts * 1.85200);
                $deg = round($apiResponse->wind_dir_degrees);
                $condition = "Bad";

                if($kts >= 11 && $kts <= 19) {
                    $condition = "Moderate";
                }

                if($kts <= 10) {
                    $condition = "Good";
                }

                $result[] = ["Wind", $deg . "° at " . $kts . " KTS (" . $kmh . " Km/H)", $condition];
            }

            if (isset($apiResponse->sky_condition)) {
                $metar = new Metar($apiResponse->raw_text, false, false);
                $metar = Helper::ToObject($metar->parse());

                if(isset($metar->clouds_report)) {
                    $report = $metar->clouds_report . ".";

                    $condition = "Unknown";
                    $result[] = ["Clouds", $report, $condition];
                }
            }


            if (isset($apiResponse->raw_text)) {
                $result[] = ["Metar", $apiResponse->raw_text , "Unknown"];
            }

        }

        return $result;
    }
}
