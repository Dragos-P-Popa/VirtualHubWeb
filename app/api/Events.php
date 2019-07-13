<?php
use Illuminate\Support\Facades\DB;

class Events {
	public function getAirportEvents($icao) {
		$events = DB::select("SELECT e.*, u.name AS 'event_manager', DATE_FORMAT(e.start, '%Y-%m-%dT%T+00:00') AS 'start', DATE_FORMAT(e.end, '%Y-%m-%dT%T+00:00') AS 'end' FROM vh_events e, users u WHERE airport = '" . $icao . "' AND e.user_id = u.id");
		$json = json_encode($events);
		$json = json_decode($json, true);

		$i = 0;

		foreach ($json as $e) {
			$s = json_decode($e["sections"]);

			$json[$i]["sections"] = $s;
			$i++;
		}


		return $json;
	}

	private function stringToURLHTML($str) {
		preg_match_all( '/[^"](http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $str, $match );

		$full_str = $str;

		foreach ($match[0] as $url) {
			$full_str = str_replace($url, "<a href='" . $url . "' target='_blank'>" . $this->longUrlClip($url) . "</a>", $full_str);
		}

		return $full_str;
	}

	private function longUrlClip($str) {
		$out = strlen($str) > 50 ? substr($str,0,45)."..." : $str;
		return $out;
	}

}