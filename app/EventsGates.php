<?php


namespace App;
use Illuminate\Database\Eloquent\Model;

class EventsGates extends Model{
    protected $table = 'vh_events_gates';

    static function Join($userID, $eventID, $gateID) {
        $occupiedGate = EventsGates::where([['event_id', '=', $eventID], ['gate_id', '=', $gateID]])->first();

        if ($occupiedGate == null) {
            $occupiedGateUser = EventsGates::where([['event_id', '=', $eventID], ['user_id', '=', $userID]])->first();

            if ($occupiedGateUser != null) {
                $occupiedGateUser->delete();
            }

            $gate = new EventsGates();
            $gate->event_id = $eventID;
            $gate->gate_id = $gateID;
            $gate->user_id = $userID;
            $gate->save();

            return Helper::ToObject(["success" => true]);
        } else {
            return Helper::ToObject(["success" => false, "reason" => "This gate is occupied."]);
        }
    }

    static function ByUser($userID){
        $events = EventsGates::where([['user_id', '=', $userID]])->get();
        return $events;
    }

    static function ById($eventID){
        $events = EventsGates::where([['id', '=', $eventID]])->get();
        return $events;
    }


}
