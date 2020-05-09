<?php

namespace App;

class ATC {


    static function ForAirport( $icao ) {
        $options = array(0 => 'Ground', 1 => 'Tower',2 => 'Unicom',3 => 'Clearance',4 => 'Approach',5 => 'Departure',6 => 'Center',7 => 'ATIS',8 => 'Aircraft',9 => 'Recorded',10 => 'Unknown',11 => 'Unused');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://infinite-flight-public-api.cloudapp.net/v1/GetSessionsInfo.aspx?apikey=78879b1d-3ba3-47de-8e50-162f35dc6e04",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            $casualID = json_decode($response)[2]->Id;
            $trainingID = json_decode($response)[3]->Id;
            $expertID= json_decode($response)[4]->Id;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://infinite-flight-public-api.cloudapp.net/v1/GetATCFacilities.aspx?apikey=78879b1d-3ba3-47de-8e50-162f35dc6e04&sessionid=".$casualID,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $casualInfo = array();
                foreach (json_decode($response) as $obj){
                    if($obj->Name == $icao){
                        $temp = array();
                        $temp['UserName'] = $obj->UserName;
                        $temp['Type']     = $options[$obj->Type];
                        $temp['StartTime']= $obj->StartTime;
                        array_push($casualInfo, $temp);
                    }
                }
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://infinite-flight-public-api.cloudapp.net/v1/GetATCFacilities.aspx?apikey=78879b1d-3ba3-47de-8e50-162f35dc6e04&sessionid=".$trainingID,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $trainingInfo = array();
                foreach (json_decode($response) as $obj){
                    if($obj->Name == $icao){
                        $temp = array();
                        $temp['UserName'] = $obj->UserName;
                        $temp['Type']     = $options[$obj->Type];
                        $temp['StartTime']= $obj->StartTime;
                        array_push($trainingInfo, $temp);
                    }
                }
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://infinite-flight-public-api.cloudapp.net/v1/GetATCFacilities.aspx?apikey=78879b1d-3ba3-47de-8e50-162f35dc6e04&sessionid=".$expertID,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $expertInfo = array();
                foreach (json_decode($response) as $obj){
                    if($obj->Name == $icao){
                        $temp = array();
                        $temp['UserName'] = $obj->UserName;
                        $temp['Type']     = $options[$obj->Type];
                        $temp['StartTime']= $obj->StartTime;
                        array_push($expertInfo, $temp);
                    }
                }
            }

            $final = array('Casual' => $casualInfo, 'Training' => $trainingInfo, 'Expert' => $expertInfo);
            return $final;
        }
    }
}
