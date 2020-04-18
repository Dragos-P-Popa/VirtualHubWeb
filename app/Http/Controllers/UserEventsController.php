<?php

namespace App\Http\Controllers;

use App\Events;
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

class UserEventsController extends Controller {
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


    public function created() {
        $events = Events::ForUser(Auth::user()->id);

        return view( 'account.userEvents',
            array("events" => $events));
    }

    public function attending() {
        $events = Events::ForUserAttending(Auth::user()->id);

        return view( 'account.userEvents',
            array("events" => $events));
    }

}
