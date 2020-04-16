<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Frequencies extends Model {
    protected $table = 'vh_frequencies';

    static function ForAirport($icao) {
		$frequencies = Frequencies::where('airport', $icao)->orderBy('name', 'ASC')->get();
        $frequencies = self::AppFormatter($frequencies);

		return $frequencies;
	}

    private static function AppFormatter($frequencies) {
        for ($i = 0; $i < count($frequencies); $i++) {
            $frequencies[$i]->app_string = "Frequency: " . $frequencies[$i]->frequency;
        }
        return $frequencies;
    }
}
