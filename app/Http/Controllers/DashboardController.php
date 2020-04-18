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

class DashboardController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	var $uptime;
	var $current_date;
	var $temp;
	var $user;

	public function __construct() {
		$this->uptime       = shell_exec( "uptime" );
		$this->current_date = date( 'l, F jS, Y | H:i' );
		$this->temp         = exec( "/opt/vc/bin/vcgencmd measure_temp" );
		$this->temp         = str_replace( 'temp=', '', $this->temp );
		$this->temp         = str_replace( '\'C', ' °C', $this->temp );
	}

	private function sendToUser($view, $data = []) {
		$this->user = Auth::user();
		$data["current_date"] = $this->current_date;

		if ( Auth::user()->role == "admin" ) {
			return view( $view, $data );
		} else {
			return view('dashboard.notauthorized');
		}
	}

	public function allUsers() {

		$users = DB::select("select * from users");
		return $this->sendToUser('dashboard.allusers', ["users" => $users]);
	}

	public function index() {
		$newUsers = DB::select("SELECT name, created_at AS 'created' FROM users ORDER BY created_at DESC LIMIT 3");

		return $this->sendToUser('dashboard.home',[
			"temp" => $this->temp,
			"uptime" => $this->uptime,
			"newUsers" => $newUsers,
		] );
	}

	public function block($id) {
		User::where('id', $id)->update(array('blocked' => 1));

		return Redirect::to('dashboard/user/all');
	}

	public function unBlock($id) {
		User::where('id', $id)->update(array('blocked' => 0));

		return Redirect::to('dashboard/user/all');
	}

	public function updateDataSet() {
		return $this->sendToUser('dashboard.updatedata');
	}

	private function convertToHoursMins($time, $format = '%02d:%02d') {
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

private function deleteDirectory($dir) {
		system('rm -rf ' . escapeshellarg($dir), $retval);
		return $retval == 0; // UNIX commands return zero on success
	}
}
