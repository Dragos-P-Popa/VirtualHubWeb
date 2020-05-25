<?php

namespace App;

class Charts {
    static $chartsStorage = "charts/";

    function __construct() {

    }


    static function ForAirport( $airport, $localOnly = false ) {
        $airport = strtoupper($airport);

        $ctx = stream_context_create( array(
            'http' =>
                array(
                    'timeout' => 1.5,  //1200 Seconds is 20 Minutes
                )
        ) );

        $json                    = [];
        $advancedChartsAvailable = false;

        if ( $localOnly == false ) {
            $url     = 'https://api.aviationapi.com/v1/charts?apt=' . $airport;
            $content = @file_get_contents( $url, false, $ctx );

            if ( $content ) {
                $json                    = json_decode( $content, true );
                $advancedChartsAvailable = true;
            }


        }

        $tempArray = array();

        $allChartsFromServer = self::getChartsFromServer();

        $matches = array_filter( $allChartsFromServer, function ( $var ) use ( $airport ) {
            return preg_match( "/\b$airport\b/i", urldecode( $var ) );
        } );

        foreach ( $matches as $match ) {
            $match = urldecode( $match );

            $name = str_replace( ".pdf", "", $match );
            $name = str_replace( ".jpg", "", $name );
            $name = str_replace( ".jpeg", "", $name );
            $name = str_replace( $airport . " ", "", $name );
            $name = str_replace( "+", " ", $name );

            if ( $name == $airport ) {
                $name = "AIRPORT INFO (CUSTOM CHART)";
            }

            $wR = Helper::ToObject( array(
                "Name"         => str_replace( "RWY", "RUNWAY", strtoupper( $name ) ),
                "OriginalName" => str_replace( " ", "+", $match ),
                "Category"     => "General",
                "Url"          => asset( self::$chartsStorage ) . "/" . urlencode( $match ),
                "Provider"     => "VirtualFlight"
            ) );

            array_push( $tempArray, $wR );
        }

        if ( $advancedChartsAvailable ) {
            if ( isset( $json[ $airport ] ) ) {
                foreach ( $json[ $airport ] as $chart ) {
                    $wR = Helper::ToObject( array(
                        "Name"     => str_replace( "RWY", "RUNWAY", $chart["chart_name"] ),
                        "Category" => "Advanced",
                        "Url"      => $chart["pdf_path"],
                        "Provider" => "aviationAPI"
                    ) );
                    array_push( $tempArray, $wR );
                }
            }
        }

        return $tempArray;

    }

    private static function getChartsFromServer() {
        return scandir( self::$chartsStorage );
    }

}
