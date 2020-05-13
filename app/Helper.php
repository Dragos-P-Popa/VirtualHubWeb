<?php


namespace App;


class Helper {
    static function ToObject($array) {
        return json_decode( json_encode( $array ) );
    }

    public static function XmlToJSON( $response ) {
        $response = str_replace( array( "\n", "\r", "\t" ), '', $response );
        $response = trim( str_replace( '"', "'", $response ) );
        $response = simplexml_load_string( $response );
        $response = json_encode( $response );
        $response = str_replace( "@attributes", "attributes", $response );
        $response = json_decode( $response, true );

        return Helper::ToObject( $response["data"] );
    }
}
