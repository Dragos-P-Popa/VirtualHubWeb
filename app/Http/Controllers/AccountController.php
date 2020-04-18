<?php

namespace App\Http\Controllers;

use App\Flightplans;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AccountController extends Controller {
    /**
     * Create a new controller instance.
     *
     *
     *  foreach ($events_attending as $eventId){
    $eventId = DB::select("select * from vh_events WHERE id = '" . $eventId ."'");
    }
     * @return void
     */
    var $current_date;

    public function __construct() {
        $this->current_date = date( 'l, F jS, Y | H:i' );

    }


    public function index() {
        $userId = Auth::user()->id;
        $user_events = DB::select("select * from vh_events WHERE user_id = '" . $userId . "' LIMIT 1");
        if (count($user_events) <= 0){
            $user_events = 'Null';
        }else {
            $user_events = get_object_vars($user_events[0]);
        }

        $events_attending = DB::select("select * from vh_events_gates WHERE user_id = '" . $userId ."'");
        foreach ($events_attending as $eventId){
            $eventId = DB::select("select * from vh_events WHERE id = '" . get_object_vars($eventId)['event_id'] ."'");
        }
        if (count($events_attending) <= 0){
            $eventId = 'Null';
        }

        return view( 'account.account',
            array("firstEvent" => $user_events),
            array("eventsAttending" => $eventId));
    }

}
