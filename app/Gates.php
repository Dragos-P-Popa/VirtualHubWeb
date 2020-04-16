<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gates extends Model
{
    protected $table = 'vh_gates';

    static function ForAirport($icao) {
        $gates = Gates::where('airport', $icao)->orderBy('size', 'ASC')->get();
        $gates = Gates::AppFormatter($gates);

        return $gates;
    }

    static function ByUID($uID) {
        $gates = Gates::where('uid', $uID)->orderBy('size', 'ASC')->get();
        return $gates;
    }

    private static function AppFormatter($gates) {
        for ($i = 0; $i < count($gates); $i++) {
            $gates[$i]->app_string = "Class: " . $gates[$i]->size . "\nAircrafts: " .  $gates[$i]->aircrafts;
            $gates[$i]->name = $gates[$i]->type . " " .  $gates[$i]->name;
        }
        return $gates;
    }
}
