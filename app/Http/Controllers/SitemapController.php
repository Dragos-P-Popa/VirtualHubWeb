<?php

namespace App\Http\Controllers;

use App\Gates;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller {
	public function generate() {
		SitemapGenerator::create( url('') )->writeToFile( "sitemap.xml" );
		return JsonResponse::create( [ "status" => "done", "file" => url( 'sitemap.xml' ) ] );
	}

	public function all_ap_page() {

		$all_ap = DB::select("SELECT icao FROM vh_airports WHERE iata != '' ORDER BY populairity DESC LIMIT 500;");

		return view("vhweb.allapsitemap",  array(
			"ap" => $all_ap
		));
	}
}
