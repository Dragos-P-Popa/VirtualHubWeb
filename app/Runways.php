<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Runways extends Model {
    protected $table = 'vh_runways';

    static function ForAirport($icao) {
        $runways = Runways::where('airport', $icao)->orderBy('length_ft', 'ASC')->get();
        $runways = self::AppFormatter($runways);
		return $runways;
	}

    private static function AppFormatter($runways) {
        for ($i = 0; $i < count($runways); $i++) {
            $runways[$i]->app_dimension = "Length: " . $runways[$i]->length_ft . " FT (" . $runways[$i]->length_km . " KM)";
            $runways[$i]->app_idents = $runways[$i]->ident1 . " - " . $runways[$i]->ident2;
        }
        return $runways;
    }
}
