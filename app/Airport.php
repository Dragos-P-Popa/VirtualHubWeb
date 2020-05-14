<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model {
    protected $table = 'vh_airports';
    public $timestamps = false;
    
    static function FullInfo( string $icao = null, string $iata = null ) {
        $airport = null;

        if ( $icao == null && $iata == null ) {
            abort( 404, "No airport provided." );
        }

        if ( $icao != null ) {
            $airport = Airport::where( 'icao', $icao )->first();
        }

        if ( $iata != null && $airport == null ) {
            $airport = Airport::where( 'iata', $iata )->first();
        }

        if ( $airport == null ) {
            abort( 404, "Airport not found." );
        }

        $airport->localdate   = Airport::LocalTime( $airport->timezone );
        $airport->weather     = Airport::Weather( $icao );
        $airport->charts      = Airport::Charts( $icao );
        $airport->gates       = Airport::Gates( $icao );
        $airport->runways     = Airport::Runways( $icao );
        $airport->frequencies = Airport::Frequencies( $icao );
        $airport->ATC         = Airport::ATC( $icao );
        //$airport->events      = Airport::Events( $icao );
        $airport->bounds      = Airport::BoundingBox( $airport->latitude, $airport->longitude, 3 );

        return $airport;
    }

    static function Info($icao) {
        $airport = Airport::where( 'icao', $icao )->first();

        if ( $airport == null ) {
            abort( 404, "Airport not found." );
        }

        return $airport;
    }

    static function Weather( string $icao ) {
        return Weather::ForAirport( $icao );
    }

    static function Charts( string $icao ) {
        return Charts::ForAirport( $icao );
    }

    static function Gates( string $icao ) {
        return Gates::ForAirport( $icao );
    }

    static function Runways( string $icao ) {
        return Runways::ForAirport( $icao );
    }

    static function Frequencies( string $icao ) {
        return Frequencies::ForAirport( $icao );
    }

    static function ATC( string $icao ) {
        return ATC::ForAirport( $icao );
    }

    //static function Events( string $icao ) {
    //    return Events::ForAirport( $icao );
    //}
    
    static function UpPopularity( string $icao){
        $pop = Airport::where('icao', $icao)->value('populairity');
        Airport::where('icao', $icao)->update(array('populairity' => $pop+1));
    }

    private static function LocalTime( $zone ) {
        date_default_timezone_set( $zone );
        return Helper::ToObject( array(
            "date"     => date( 'm-d-y' ),
            "time"     => date( 'h:i a' ),
            "time24"   => date( 'H:i' ),
            "datefull" => date( 'l, F jS, Y | H:i' )
        ) );
    }

    // Distance is in km, alat and alon are in degrees
    private static function BoundingBox($lat_degrees,$lon_degrees,$distanceInMiles) {

        $radius = 3963.1; // of earth in miles

        // bearings - FIX
        $due_north = deg2rad(0);
        $due_south = deg2rad(180);
        $due_east = deg2rad(90);
        $due_west = deg2rad(270);

        // convert latitude and longitude into radians
        $lat_r = deg2rad($lat_degrees);
        $lon_r = deg2rad($lon_degrees);

        // find the northmost, southmost, eastmost and westmost corners $distance_in_miles away
        // original formula from
        // http://www.movable-type.co.uk/scripts/latlong.html

        $northmost  = asin(sin($lat_r) * cos( $distanceInMiles / $radius) + cos($lat_r) * sin ( $distanceInMiles / $radius) * cos($due_north));
        $southmost  = asin(sin($lat_r) * cos( $distanceInMiles / $radius) + cos($lat_r) * sin ( $distanceInMiles / $radius) * cos($due_south));

        $eastmost = $lon_r + atan2(sin($due_east)*sin( $distanceInMiles / $radius) * cos($lat_r), cos( $distanceInMiles / $radius) - sin($lat_r) * sin($lat_r));
        $westmost = $lon_r + atan2(sin($due_west)*sin( $distanceInMiles / $radius) * cos($lat_r), cos( $distanceInMiles / $radius) - sin($lat_r) * sin($lat_r));


        $northmost = rad2deg($northmost);
        $southmost = rad2deg($southmost);
        $eastmost = rad2deg($eastmost);
        $westmost = rad2deg($westmost);

        // sort the lat and long so that we can use them for a between query
        if ($northmost > $southmost) {
            $lat1 = $southmost;
            $lat2 = $northmost;

        } else {
            $lat1 = $northmost;
            $lat2 = $southmost;
        }


        if ($eastmost > $westmost) {
            $lon1 = $westmost;
            $lon2 = $eastmost;

        } else {
            $lon1 = $eastmost;
            $lon2 = $westmost;
        }

        return array($lat1,$lat2,$lon1,$lon2);
    }
}
