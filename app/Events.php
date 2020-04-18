<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Events extends Model {
    protected $table = 'vh_events';

    static function ForAirport(string $icao) {
        $events = Events::where('airport', $icao)->get();
        $events = self::DateFormatter($events);
        $events = self::LinkManagers($events);
        $events = self::SetSections($events);

        return $events;
    }

    static function ForUser(int $usedID = null ) {
        if($usedID == null && Auth::id() == null) {
            abort(404, 'User not found.');
        }

        if($usedID == null) {
            $usedID = Auth::id();
        }

        $events = Events::where('user_id', $usedID)->get();
        $events = self::DateFormatter($events);
        $events = self::LinkManagers($events);
        $events = self::SetSections($events);

        return $events;
    }

    static function ForUserAttending($userID){
        if($userID == null && Auth::id() == null) {
            abort(404, 'User not found.');
        }
        if($userID == null) {
            $userID = Auth::id();
        }

        $events = array();
        $gatesOccupied = EventsGates::ByUser($userID);
        foreach($gatesOccupied as $eventAttending){
            array_push($events, Events::where('id', $eventAttending['event_id'])->get()[0]);
        }

        return $events;
    }

    static function ByID($id) {
        $events = Events::where('id', $id)->get();
        $events = self::DateFormatter($events);
        $events = self::LinkManagers($events);
        $events = self::SetSections($events);

        return $events;
    }

    private static function SetSections($events) {
        for ($i = 0; $i < count($events); $i++) {
            $events[$i]->sections = json_decode( $events[$i]->sections );
        }
        return $events;
    }

    private static function LinkManagers($events) {
        for ($i = 0; $i < count($events); $i++) {
            $events[$i]->event_manager = User::find($events[$i]->user_id)->name;
        }
        return $events;
    }

	private static function DateFormatter($events) {
        for ($i = 0; $i < count($events); $i++) {
            $events[$i]->start = gmdate('Y-m-d\TH:i:s+00:00', strtotime($events[$i]->start));
            $events[$i]->end = gmdate('Y-m-d\TH:i:s+00:00', strtotime($events[$i]->end));
        }
        return $events;
    }

    function delete() {
        $OccupiedGates = EventsGates::Occupied($this->id);
        $OccupiedGates->delete();
        return parent::delete();
    }
}
