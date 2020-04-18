<?php

namespace App\Http\Controllers;

use App\Gates;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller {
	public function generate() {
		SitemapGenerator::create( url( '' ) )
		                ->hasCrawled( function ( Url $url ) {
		                	$s = $url->segment( 1 );
			                if ( $s === 'api' || $s === 'dashboard' || $s === 'password' || $s === 'storage' ) {
				                return;
			                }

			                return $url;
		                } )
		                ->writeToFile( "sitemap.xml" );

		return JsonResponse::create( [ "status" => "done", "file" => url( 'sitemap.xml' ) ] );
	}

	public function all_ap_page() {

		$all_ap = DB::select( "SELECT icao FROM vh_airports WHERE iata != '' ORDER BY populairity DESC LIMIT 500;" );

		return view( "vhweb.allapsitemap", array(
			"ap" => $all_ap
		) );
	}
}
