<?php


namespace App;

class Metar
{
    /*
     * Array of decoded result, by default all parameters is null.
     */
    private $result = array
    (
        'raw'                      => NULL,
        'taf'                      => NULL,
        'taf_flag'                 => NULL,
        'station'                  => NULL,
        'observed_date'            => NULL,
        'observed_day'             => NULL,
        'observed_time'            => NULL,
        'observed_age'             => NULL,
        'wind_speed'               => NULL,
        'wind_gust_speed'          => NULL,
        'wind_direction'           => NULL,
        'wind_direction_label'     => NULL,
        'wind_direction_varies'    => NULL,
        'varies_wind_min'          => NULL,
        'varies_wind_min_label'    => NULL,
        'varies_wind_max'          => NULL,
        'varies_wind_max_label'    => NULL,
        'visibility'               => NULL,
        'visibility_report'        => NULL,
        'visibility_min'           => NULL,
        'visibility_min_direction' => NULL,
        'runways_visual_range'     => NULL,
        'present_weather'          => NULL,
        'present_weather_report'   => NULL,
        'clouds'                   => NULL,
        'clouds_report'            => NULL,
        'cloud_height'             => NULL,
        'cavok'                    => NULL,
        'temperature'              => NULL,
        'temperature_f'            => NULL,
        'dew_point'                => NULL,
        'dew_point_f'              => NULL,
        'humidity'                 => NULL,
        'heat_index'               => NULL,
        'heat_index_f'             => NULL,
        'wind_chill'               => NULL,
        'wind_chill_f'             => NULL,
        'barometer'                => NULL,
        'barometer_in'             => NULL,
        'recent_weather'           => NULL,
        'recent_weather_report'    => NULL,
        'runways_report'           => NULL,
        'runways_snoclo'           => NULL,
        'wind_shear_all_runways'   => NULL,
        'wind_shear_runways'       => NULL,
        'forecast_temperature_min' => NULL,
        'forecast_temperature_max' => NULL,
        'trends'                   => NULL,
        'remarks'                  => NULL,
    );

    /*
     * Methods used for parsing in the order of data
     */
    private $method_names = array
    (
        'taf',
        'station',
        'time',
        'station_type',
        'wind',
        'varies_wind',
        'visibility',
        'visibility_min',
        'runway_vr',
        'present_weather',
        'clouds',
        'temperature',
        'pressure',
        'recent_weather',
        'runways_report',
        'wind_shear',
        'forecast_temperature',
        'trends',
        'remarks',
    );

    /*
     * Interpretation of weather conditions intensity codes.
     */
    private $weather_intensity_codes = array
    (
        '-'  => 'light',
        '+'  => 'strong',
        'VC' => 'in the vicinity',
    );

    /*
     * Interpretation of weather conditions characteristics codes.
     */
    private $weather_char_codes = array
    (
        'MI' => 'shallow',
        'PR' => 'partial',
        'BC' => 'patches of',
        'DR' => 'low drifting',
        'BL' => 'blowing',
        'SH' => 'showers of',
        'TS' => 'thunderstorms',
        'FZ' => 'freezing',
    );

    /*
     * Interpretation of weather conditions type codes.
     */
    private $weather_type_codes = array
    (
        'DZ' => 'drizzle',
        'RA' => 'rain',
        'SN' => 'snow',
        'SG' => 'snow grains',
        'IC' => 'ice crystals',
        'PE' => 'ice pellets',
        'GR' => 'hail',
        'GS' => 'small hail', // and/or snow pellets
        'UP' => 'unknown',
        'BR' => 'mist',
        'FG' => 'fog',
        'FU' => 'smoke',
        'VA' => 'volcanic ash',
        'DU' => 'widespread dust',
        'SA' => 'sand',
        'HZ' => 'haze',
        'PY' => 'spray',
        'PO' => 'well-developed dust/sand whirls',
        'SQ' => 'squalls',
        'FC' => 'funnel cloud, tornado, or waterspout',
        'SS' => 'sandstorm/duststorm',
    );

    /*
     * Interpretation of cloud cover codes.
     */
    private $cloud_codes = array
    (
        'NSW'  => 'No significant weather are observed',
        'NSC'  => 'No significant clouds are observed',
        'NCD'  => 'No cloud detected',
        'SKC'  => 'No significant changes expected',
        'CLR'  => 'Clear skies',
        'NOBS' => 'No observation',
        //
        'FEW'  => 'A few',
        'SCT'  => 'Scattered',
        'BKN'  => 'Broken sky',
        'OVC'  => 'Overcast sky',
        //
        'VV'   => 'Vertical visibility',
    );

    /*
     * Interpretation of cloud cover type codes.
     */
    private $cloud_type_codes = array
    (
        'CB'  => 'cumulonimbus',
        'TCU' => 'towering cumulus',
    );
    /*
     * Interpretation of runway visual range tendency codes.
     */
    private $rvr_tendency_codes = array
    (
        'D' => 'decreasing',
        'U' => 'increasing',
        'N' => 'no tendency',
    );

    /*
     * Interpretation of runway visual range prefix codes.
     */
    private $rvr_prefix_codes = array
    (
        'P' => 'more',
        'M' => 'less',
    );

    /*
     * Interpretation of runway runway deposits codes.
     */
    private $runway_deposits_codes = array
    (
        '0' => 'clear and dry',
        '1' => 'damp',
        '2' => 'wet or water patches',
        '3' => 'rime or frost covered',
        '4' => 'dry snow',
        '5' => 'wet snow',
        '6' => 'slush',
        '7' => 'ice',
        '8' => 'compacted or rolled snow',
        '9' => 'frozen ruts or ridges',
        '/' => 'not reported',
    );

    /*
     * Interpretation of runway runway deposits extent codes.
     */
    private $runway_deposits_extent_codes = array
    (
        '1' => 'from 10% or less',
        '2' => 'from 11% to 25%',
        '5' => 'from 26% to 50%',
        '9' => 'from 51% to 100%',
        '/' => NULL,
    );

    /*
     * Interpretation of runway runway deposits depth codes.
     */
    private $runway_deposits_depth_codes = array
    (
        '00' => 'less than 1 mm',
        '92' => '10 cm',
        '93' => '15 cm',
        '94' => '20 cm',
        '95' => '25 cm',
        '96' => '30 cm',
        '97' => '35 cm',
        '98' => '40 cm or more',
        '99' => 'closed',
        '//' => NULL,
    );

    /*
     * Interpretation of runway runway friction codes.
     */
    private $runway_friction_codes = array
    (
        '91' => 'poor',
        '92' => 'medium/poor',
        '93' => 'medium',
        '94' => 'medium/good',
        '95' => 'good',
        '99' => 'figures unreliable',
        '//' => NULL,
    );

    /*
     * Trends time codes.
     */
    private $trends_flag_codes = array
    (
        'BECMG' => 'expected to arise soon',
        'TEMPO' => 'expected to arise temporarily',
        'INTER' => 'expected to arise intermittent',
        'PROV'  => 'provisional forecast',
        'CNL'   => 'cancelled forecast',
        'NIL'   => 'nil forecast',
    );

    /*
     * Trends time codes.
     */
    private $trends_time_codes = array
    (
        'AT' => 'at',
        'FM' => 'from',
        'TL' => 'until',
    );

    /*
     * Interpretation of compass degrees codes.
     */
    private $direction_codes = array
    (
        'N', 'NNE', 'NE', 'ENE',
        'E', 'ESE', 'SE', 'SSE',
        'S', 'SSW', 'SW', 'WSW',
        'W', 'WNW', 'NW', 'NNW',
    );

    /*
     * Debug and parse errors information.
     */
    private $errors = NULL;
    private $debug  = NULL;
    private $debug_enabled;

    /*
     * Other variables.
     */
    private $raw;
    private $raw_parts = array();
    private $method    = 0;
    private $part      = 0;


    /**
     * This method provides METAR and TAF information, you want to parse.
     *
     * Examples of raw METAR for test:
     * UMMS 231530Z 21002MPS 2100 BR OVC002 07/07 Q1008 R13/290062 NOSIG RMK QBB070
     * UWSS 231500Z 14007MPS 9999 -SHRA BR BKN033CB OVC066 03/M02 Q1019 R12/220395 NOSIG RMK QFE752
     * UWSS 241200Z 12003MPS 0300 R12/1000 DZ FG VV003CB 05/05 Q1015 R12/220395 NOSIG RMK QFE749
     * UATT 231530Z 18004MPS 130V200 CAVOK M03/M08 Q1033 R13/0///60 NOSIG RMK QFE755/1006
     * KEYW 231553Z 04008G16KT 10SM FEW060 28/22 A3002 RMK AO2 SLP166 T02780222
     * EFVR 231620Z AUTO 19002KT 5000 BR FEW003 BKN005 OVC007 09/08 Q0998
     * KTTN 051853Z 04011KT M1/2SM VCTS SN FZFG BKN003 OVC010 M02/M02 A3006 RMK AO2 TSB40 SLP176 P0002 T10171017=
     * UEEE 072000Z 00000MPS 0150 R23L/0500 R10/1000VP1800D FG VV003 M50/M53 Q1028 RETSRA R12/290395 R31/CLRD// R/SNOCLO WS RWY10L WS RWY11L TEMPO 4000 RADZ BKN010 RMK QBB080 OFE745
     * UKDR 251830Z 00000MPS CAVOK 08/07 Q1019 3619//60 NOSIG
     * UBBB 251900Z 34015KT 9999 FEW013 BKN030 16/14 Q1016 88CLRD70 NOSIG
     * UMMS 251936Z 19002MPS 9999 SCT006 OVC026 06/05 Q1015 R31/D NOSIG RMK QBB080 OFE745
     */
    public function __construct($raw, $taf = FALSE, $debug = FALSE, $icao = TRUE)
    {
        $this->debug_enabled = $debug;

        // Raw is a ICAO code
        if ($icao AND preg_match('@^([A-Z]{1}[A-Z0-9]{3})$@', $raw))
        {
            $raw = $this->download_raw($raw, $taf);
        }

        if (empty($raw))
        {
            throw new Exception('The METAR or TAF information is not presented.');
        }

        $raw_lines = explode("\n", $raw, 2);

        if (isset($raw_lines[1]))
        {
            $raw = trim($raw_lines[1]);

            // Get observed time from a file data
            $observed_time = strtotime(trim($raw_lines[0]));

            if ($observed_time != 0)
            {
                $this->set_observed_date($observed_time);

                $this->set_debug('Observation date is set from the METAR/TAF in first line of the file content: '.trim($raw_lines[0]));
            }
        }
        else
        {
            $raw = trim($raw_lines[0]);
        }

        $this->raw = rtrim(trim(preg_replace('/[\s\t]+/s', ' ', $raw)), '=');

        if ($taf)
        {
            $this->set_debug('Infromation presented as TAF or trend.');
        }
        else
        {
            $this->set_debug('Infromation presented as METAR.');
        }

        $this->set_result_value('taf', $taf);
        $this->set_result_value('raw', $this->raw);
    }

    /**
     * Gets the value from result array as class property.
     */
    public function __get($parameter)
    {
        if (isset($this->result[$parameter]))
        {
            return $this->result[$parameter];
        }

        return NULL;
    }

    /**
     * Parses the METAR or TAF information and returns result array.
     */
    public function parse()
    {
        $this->raw_parts = explode(' ', $this->raw);

        $current_method = 0;

        // See parts
        while ($this->part < sizeof($this->raw_parts))
        {
            $this->method = $current_method;

            // See methods
            while ($this->method < sizeof($this->method_names))
            {
                $method = 'get_'.$this->method_names[$this->method];
                $token  = $this->raw_parts[$this->part];

                if ($this->$method($token) === TRUE)
                {
                    $this->set_debug('Token "'.$token.'" is parsed by method: '.$method.', '.
                                     ($this->method - $current_method).' previous methods skipped.');

                    $current_method = $this->method;

                    $this->method++;

                    break;
                }

                $this->method++;
            }

            if ($current_method != $this->method - 1)
            {
                $this->set_error('Unknown token: '.$this->raw_parts[$this->part]);
                $this->set_debug('Token "'.$this->raw_parts[$this->part].'" is NOT PARSED, '.
                                 ($this->method - $current_method).' methods attempted.');
            }

            $this->part++;
        }

        // Delete null values from the TAF report
        if ($this->result['taf'] === TRUE)
        {
            foreach ($this->result as $parameter => $value)
            {
                if (is_null($value))
                {
                    unset($this->result[$parameter]);
                }
            }
        }

        return $this->result;
    }

    /**
     * Returns array with debug information.
     */
    public function debug()
    {
        return $this->debug;
    }

    /**
     * Returns array with parse errors.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * This method downloads METAR or TAF information for a given station from the
     * National Weather Service. It assumes that the station exists.
     */
    private function download_raw($icao, $taf = FALSE)
    {
        if ($taf)
        {
            $url = 'http://tgftp.nws.noaa.gov/data/forecasts/taf/stations/'.$icao.'.TXT';
        }
        else
        {
            $url = 'http://tgftp.nws.noaa.gov/data/observations/metar/stations/'.$icao.'.TXT';
        }

        if (!$raw = @file_get_contents($url))
        {
            throw new Exception('Error while downloading METAR or TAF information');
        }

        $this->set_debug('METAR/TAF infromation downloaded from: '.$url);

        return $raw;
    }

    /**
     * This method formats observation date and time in the local time zone of server,
     * the current local time on server, and time difference since observation. $time_utc is a
     * UNIX timestamp for Universal Coordinated Time (Greenwich Mean Time or Zulu Time).
     */
    private function set_observed_date($time_utc)
    {
        $local = $time_utc + date('Z');
        $now   = time();

        $this->set_result_value('observed_date', date('r', $local)); // or "D M j, H:i T"

        $time_diff = floor(($now - $local) / 60);

        if ($time_diff < 91)
        {
            $this->set_result_value('observed_age', $time_diff.' min. ago');
        }
        else
        {
            $this->set_result_value('observed_age', floor($time_diff / 60).':'.sprintf("%02d", $time_diff % 60).' hr. ago');
        }
    }

    /**
     * Sets the new value to parameter in result array.
     */
    private function set_result_value($parameter, $value, $only_is_null = FALSE)
    {
        if ($only_is_null)
        {
            if (is_null($this->result[$parameter]))
            {
                $this->result[$parameter] = $value;

                $this->set_debug('Set value "'.$value.'" ('.gettype($value).') for null parameter: '.$parameter);
            }
        }
        else
        {
            $this->result[$parameter] = $value;

            $this->set_debug('Set value "'.$value.'" ('.gettype($value).') for parameter: '.$parameter);
        }
    }

    /**
     * Sets the data group to parameter in result array.
     */
    private function set_result_group($parameter, $group)
    {
        if (is_null($this->result[$parameter]))
        {
            $this->result[$parameter] = array();
        }

        array_push($this->result[$parameter], $group);

        $this->set_debug('Add new group value ('.gettype($group).') for parameter: '.$parameter);
    }

    /**
     * Sets the report text to parameter in result array.
     */
    private function set_result_report($parameter, $report, $separator = ',')
    {
        $this->result[$parameter] .= $separator.' '.$report;

        if (!is_null($this->result[$parameter]))
        {
            $this->result[$parameter] = ucfirst(ltrim($this->result[$parameter], ' '.$separator));
        }

        $this->set_debug('Add group report value "'.$report.'" for parameter: '.$parameter);
    }

    /**
     * Adds the debug text to debug information array.
     */
    private function set_debug($text)
    {
        if ($this->debug_enabled)
        {
            if (is_null($this->debug))
            {
                $this->debug = array();
            }

            array_push($this->debug, $text);
        }
    }

    /**
     * Adds the error text to parse errors array.
     */
    private function set_error($text)
    {
        if (is_null($this->errors))
        {
            $this->errors = array();
        }

        array_push($this->errors, $text);
    }

    // --------------------------------------------------------------------
    // Methods for parsing raw parts
    // --------------------------------------------------------------------

    /**
     * Decodes TAF code if present.
     */
    private function get_taf($part)
    {
        if ($part != 'TAF')
        {
            return FALSE;
        }

        if ($this->raw_parts[$this->part + 1] == 'COR' OR $this->raw_parts[$this->part + 1] == 'AMD')
        {
            $this->set_result_value('taf_flag', $this->raw_parts[$this->part + 1], TRUE);

            $this->part++;
        }

        $this->set_debug('TAF infromation detected.');

        $this->set_result_value('taf', TRUE);

        return TRUE;
    }

    /**
     * Decodes station code.
     */
    private function get_station($part)
    {
        if (!preg_match('@^([A-Z]{1}[A-Z0-9]{3})$@', $part, $found))
        {
            return FALSE;
        }

        $this->set_result_value('station', $found[1]);

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes observation time.
     * Format is ddhhmmZ where dd = day, hh = hours, mm = minutes in UTC time.
     */
    private function get_time($part)
    {
        if (!preg_match('@^([0-9]{2})([0-9]{2})([0-9]{2})Z$@', $part, $found))
        {
            return FALSE;
        }

        $day    = intval($found[1]);
        $hour   = intval($found[2]);
        $minute = intval($found[3]);

        if (is_null($this->result['observed_date']))
        {
            // Get observed time from a METAR/TAF part
            $observed_time = mktime($hour, $minute, 0, date('n'), $day, date('Y'));

            // Take one month, if the observed day is greater than the current day
            if ($day > date('j'))
            {
                $observed_time = strtotime('-1 month');
            }

            $this->set_observed_date($observed_time);

            $this->set_debug('Observation date is set from the METAR/TAF information (presented in format: ddhhmmZ)');
        }

        $this->set_result_value('observed_day', $day);
        $this->set_result_value('observed_time', $found[2].':'.$found[3].' UTC');

        $this->method++;

        return TRUE;
    }

    /**
     * Ignore station type if present.
     */
    private function get_station_type($part)
    {
        if ($part != 'AUTO' AND $part != 'COR')
        {
            return FALSE;
        }

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes wind direction and speed information.
     * Format is dddssKT where ddd = degrees from North, ss = speed, KT for knots,
     * or dddssGggKT where G stands for gust and gg = gust speed. (ss or gg can be a 3-digit number.)
     * KT can be replaced with MPH for meters per second or KMH for kilometers per hour.
     */
    private function get_wind($part)
    {
        if (!preg_match('@^([0-9]{3}|VRB|///)P?([/0-9]{2,3}|//)(GP?([0-9]{2,3}))?(KT|MPS|KPH)@', $part, $found))
        {
            return FALSE;
        }

        $this->set_result_value('wind_direction_varies', FALSE, TRUE);

        if ($found[1] == '///' AND $found[2] == '//') { } // handle the case where nothing is observed
        else
        {
            $unit = $found[5];

            // Speed
            $this->set_result_value('wind_speed', $this->convert_speed($found[2], $unit));

            // Direction
            if ($found[1] == 'VRB')
            {
                $this->set_result_value('wind_direction_varies', TRUE);
            }
            else
            {
                $direction = intval($found[1]);

                if ($direction >= 0 AND $direction <= 360)
                {
                    $this->set_result_value('wind_direction', $direction);
                    $this->set_result_value('wind_direction_label', $this->convert_direction_label($direction));
                }
            }

            // Speed variations (gust speed)
            if (isset($found[4]) AND !empty($found[4]))
            {
                $this->set_result_value('wind_gust_speed', $this->convert_speed($found[4], $unit));
            }
        }

        $this->method++;

        return TRUE;
    }

    /*
     * Decodes varies wind direction information if present.
     * Format is fffVttt where V stands for varies from fff degrees to ttt degrees.
     */
    private function get_varies_wind($part)
    {
        if (!preg_match('@^([0-9]{3})V([0-9]{3})$@', $part, $found))
        {
            return FALSE;
        }

        $min_direction = intval($found[1]);
        $max_direction = intval($found[2]);

        if ($min_direction >= 0 AND $min_direction <= 360)
        {
            $this->set_result_value('varies_wind_min', $min_direction);
            $this->set_result_value('varies_wind_min_label', $this->convert_direction_label($min_direction));
        }

        if ($max_direction >= 0 AND $max_direction <= 360)
        {
            $this->set_result_value('varies_wind_max', $max_direction);
            $this->set_result_value('varies_wind_max_label', $this->convert_direction_label($max_direction));
        }

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes visibility information. This function will be called a second time
     * if visibility is limited to an integer mile plus a fraction part.
     * Format is mmSM for mm = statute miles, or m n/dSM for m = mile and n/d = fraction of a mile,
     * or just a 4-digit number nnnn (with leading zeros) for nnnn = meters.
     */
    private function get_visibility($part)
    {
        if (!preg_match('@^(CAVOK|([0-9]{4})|(M)?([0-9]{0,2})?(([1357])/(2|4|8|16))?SM|////)$@', $part, $found))
        {
            return FALSE;
        }

        $this->set_result_value('cavok', FALSE, TRUE);

        // Cloud and visibilty OK or ICAO visibilty greater than 10 km
        if ($found[1] == 'CAVOK' OR $found[1] == '9999')
        {
            $this->set_result_value('visibility', 10000);
            $this->set_result_value('visibility_report', 'Greater than 10 km');

            if ($found[1] == 'CAVOK')
            {
                $this->set_result_value('cavok', TRUE);

                $this->method += 4; // can skip the next 4 methods: visibility_min, runway_vr, present_weather, clouds
            }
        }
        elseif ($found[1] == '////') { } // information not available
        else
        {
            $prefix = '';

            // ICAO visibility (in meters)
            if (isset($found[2]) AND !empty($found[2]))
            {
                $visibility = intval($found[2]);
            }
            // US visibility (in miles)
            else
            {
                if (isset($found[3]) AND !empty($found[3]))
                {
                    $prefix = 'Less than ';
                }

                if (isset($found[7]) AND !empty($found[7]))
                {
                    $visibility = intval($found[4]) + intval($found[6]) / intval($found[7]);
                }
                else
                {
                    $visibility = intval($found[4]);
                }

                $visibility = $this->convert_distance($visibility, 'SM'); // convert to meters
            }

            $unit = ' meters';

            if ($visibility <= 1)
            {
                $unit = ' meter';
            }

            $this->set_result_value('visibility', $visibility);
            $this->set_result_value('visibility_report', $prefix.$visibility.$unit);
        }

        return TRUE;
    }

    /**
     * Decodes visibility minimum value and direction if present.
     * Format is vvvvDD for vvvv = the minimum horizontal visibility in meters
     * (if the visibility is better than 10 km, 9999 is used. 9999 means a minimum
     * visibility of 50 m or less), and for DD = the approximate direction of minimum and
     * maximum visibility is given as one of eight compass points (N, SW, ...).
     */
    private function get_visibility_min($part)
    {
        if (!preg_match('@^([0-9]{4})(NE|NW|SE|SW|N|E|S|W|)?$@', $part, $found))
        {
            return FALSE;
        }

        $this->set_result_value('visibility_min', $found[1]);

        if (isset($found[2]) AND !empty($found[2]))
        {
            $this->set_result_value('visibility_min_direction', $found[2]);
        }

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes runway visual range information if present.
     * Format is Rrrr/vvvvFT where rrr = runway number, vvvv = visibility,
     * and FT = the visibility in feet.
     */
    private function get_runway_vr($part)
    {
        if (!preg_match('@^R([0-9]{2}[LCR]?)/(([PM])?([0-9]{4})V)?([PM])?([0-9]{4})(FT)?/?([UDN]?)$@', $part, $found))
        {
            return FALSE;
        }

        if (intval($found[1]) > 36 OR intval($found[1]) < 1)
        {
            return FALSE;
        }

        $unit = 'M';

        if (isset($found[6]) AND $found[6] == 'FT')
        {
            $unit = 'FT';
        }

        $observed = array
        (
            'runway'          => $found[1],
            'variable'        => NULL,
            'variable_prefix' => NULL,
            'interval_min'    => NULL,
            'interval_max'    => NULL,
            'tendency'        => NULL,
            'report'          => NULL,
        );

        // Runway past tendency
        if (isset($found[8]) AND isset($this->rvr_tendency_codes[$found[8]]))
        {
            $observed['tendency'] = $found[8];
        }

        // Runway visual range
        if (isset($found[6]))
        {
            if (!empty($found[4]))
            {
                $observed['interval_min'] = $this->convert_distance($found[4], $unit);
                $observed['interval_max'] = $this->convert_distance($found[6], $unit);

                if (!empty($found[5]))
                {
                    $observed['variable_prefix'] = $found[5];
                }
            }
            else
            {
                $observed['variable'] = $this->convert_distance($found[6], $unit);
            }
        }

        // Runway visual range report
        if (!empty($observed['runway']))
        {
            $report = array();

            if ($observed['variable'] !== NULL)
            {
                $unit = ' meters';

                if ($observed['variable'] <= 1)
                {
                    $unit = ' meter';
                }
                $report[] = $observed['variable'].$unit;
            }
            elseif (!is_null($observed['interval_min']) AND !is_null($observed['interval_max']))
            {
                if (isset($this->rvr_prefix_codes[$observed['variable_prefix']]))
                {
                    $report[] = 'varying from a min. of '.$observed['interval_min'].' meters until a max. of '.
                                $this->rvr_prefix_codes[$observed['variable_prefix']].' that '.
                                $observed['interval_max'].' meters';
                }
                else
                {
                    $report[] = 'varying from a min. of '.$observed['interval_min'].' meters until a max. of '.
                                $observed['interval_max'].' meters';
                }
            }

            if (!is_null($observed['tendency']))
            {
                if (isset($this->rvr_tendency_codes[$observed['tendency']]))
                {
                    $report[] = 'and '.$this->rvr_tendency_codes[$observed['tendency']];
                }
            }

            $observed['report'] = ucfirst(implode(' ', $report));
        }

        $this->set_result_group('runways_visual_range', $observed);

        return TRUE;
    }

    /**
     * Decodes present weather conditions if present. This function maybe called several times
     * to decode all conditions. To learn more about weather condition codes, visit section
     * 12.6.8 - Present Weather Group of the Federal Meteorological Handbook No. 1 at
     * www.nws.noaa.gov/oso/oso1/oso12/fmh1/fmh1ch12.htm
     */
    private function get_present_weather($part)
    {
        return $this->decode_weather($part, 'present');
    }

    /**
     * Decodes cloud cover information if present. This function maybe called several times
     * to decode all cloud layer observations. Only the last layer is saved.
     * Format is SKC or CLR for clear skies, or cccnnn where ccc = 3-letter code and
     * nnn = height of cloud layer in hundreds of feet. 'VV' seems to be used for
     * very low cloud layers.
     */
    private function get_clouds($part)
    {
        if (!preg_match('@^((NSW|NSC|NCD|CLR|SKC|NOBS)|((VV|FEW|SCT|BKN|OVC|///)([0-9]{3}|///)(CB|TCU|///)?))$@', $part, $found))
        {
            return FALSE;
        }

        $observed = array
        (
            'amount' => NULL,
            'height' => NULL,
            'type'   => NULL,
            'report' => NULL,
        );

        // Clear skies or no observation
        if (isset($found[2]) AND !empty($found[2]))
        {
            if (isset($this->cloud_codes[$found[2]]))
            {
                $observed['amount'] = $found[2];
            }
        }
        // Cloud cover observed
        elseif (isset($found[5]) AND !empty($found[5]))
        {
            $observed['height'] = $this->convert_distance($found[5] * 100, 'FT'); // convert feet to meters

            // Cloud height
            if (is_null($this->result['cloud_height']) OR $observed['height'] < $this->result['cloud_height'])
            {
                $this->set_result_value('cloud_height', $observed['height']);
            }

            if (isset($this->cloud_codes[$found[4]]))
            {
                $observed['amount'] = $found[4];
            }
        }

        // Type
        if (isset($found[6]) AND !empty($found[6]))
        {
            if (isset($this->cloud_type_codes[$found[6]]) AND $found[4] != 'VV')
            {
                $observed['type'] = $found[6];
            }
        }

        // Build clouds report
        if (!is_null($observed['amount']))
        {
            $report = array();

            $report[] = $this->cloud_codes[$observed['amount']];

            if ($observed['height'])
            {
                if (!is_null($observed['type']))
                {
                    $report[] = 'at '.$observed['height'].' meters, '.$this->cloud_type_codes[$observed['type']];
                }
                else
                {
                    $report[] = 'at '.$observed['height'].' meters';
                }
            }

            $report = implode(' ', $report);

            $observed['report'] = ucfirst($report);

            $this->set_result_report('clouds_report', $report);
        }

        $this->set_result_group('clouds', $observed);

        return TRUE;
    }

    /**
     * Decodes temperature and dew point information. Relative humidity is calculated. Also,
     * depending on the temperature, Heat Index or Wind Chill Temperature is calculated.
     * Format is tt/dd where tt = temperature and dd = dew point temperature. All units are
     * in Celsius. A 'M' preceeding the tt or dd indicates a negative temperature. Some
     * stations do not report dew point, so the format is tt/ or tt/XX.
     */
    private function get_temperature($part)
    {
        if (!preg_match('@^(M?[0-9]{2})/(M?[0-9]{2}|[X]{2})?@', $part, $found))
        {
            return FALSE;
        }

        // Set clouds and weather reports if its not observed (e.g. clear and dry)
        $this->set_result_value('clouds_report', 'Clear skies', TRUE);
        $this->set_result_value('present_weather_report', 'Dry', TRUE);

        // Temperature
        $temperature_c = intval(strtr($found[1], 'M', '-'));
        $temperature_f = round(1.8 * $temperature_c + 32);

        $this->set_result_value('temperature', $temperature_c);
        $this->set_result_value('temperature_f', $temperature_f);

        $this->calculate_wind_chill($temperature_f);

        // Dew point
        if (isset($found[2]) AND strlen($found[2]) != 0 AND $found[2] != 'XX')
        {
            $dew_point_c = intval(strtr($found[2], 'M', '-'));
            $dew_point_f = round(1.8 * $dew_point_c + 32);
            $rh          = round(100 * pow((112 - (0.1 * $temperature_c) + $dew_point_c) / (112 + (0.9 * $temperature_c)), 8));

            $this->set_result_value('dew_point', $dew_point_c);
            $this->set_result_value('dew_point_f', $dew_point_f);
            $this->set_result_value('humidity', $rh);

            $this->calculate_heat_index($temperature_f, $rh);
        }

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes altimeter or barometer information.
     * Format is Annnn where nnnn represents a real number as nn.nn in inches of Hg,
     * or Qpppp where pppp = hectoPascals.
     * Some other common conversion factors:
     *   1 millibar = 1 hPa
     *   1 in Hg    = 0.02953 hPa
     *   1 mm Hg    = 25.4 in Hg     = 0.750062 hPa
     *   1 lb/sq in = 0.491154 in Hg = 0.014504 hPa
     *   1 atm      = 0.33421 in Hg  = 0.0009869 hPa
     */
    private function get_pressure($part)
    {
        if (!preg_match('@^(Q|A)(////|[0-9]{4})@', $part, $found))
        {
            return FALSE;
        }

        $pressure = intval($found[2]);

        if ($found[1] == 'A')
        {
            $pressure /= 100;
        }

        $this->set_result_value('barometer', $pressure); // units are hPa
        $this->set_result_value('barometer_in', round(0.02953 * $pressure, 2)); // convert to in Hg

        $this->method++;

        return TRUE;
    }

    /**
     * Decodes recent weather conditions if present.
     * Format is REww where ww = Weather phenomenon code (see get_present_weather above).
     */
    private function get_recent_weather($part)
    {
        return $this->decode_weather($part, 'recent', 'RE');
    }

    /**
     * Decodes runways report information if present.
     * Format rrrECeeBB or Rrrr/ECeeBB where rr = runway number, E = deposits,
     * C = extent of deposit, ee = depth of deposit, BB = friction coefficient.
     */
    private function get_runways_report($part)
    {
        if (!preg_match('@^R?(/?(SNOCLO)|([0-9]{2}[LCR]?)/?(CLRD|([0-9]{1}|/)([0-9]{1}|/)([0-9]{2}|//))([0-9]{2}|//))$@', $part, $found))
        {
            return FALSE;
        }

        $this->set_result_value('runways_snoclo', FALSE, TRUE);

        // Airport closed due to snow
        if (isset($found[2]) AND ($found[2] == 'SNOCLO'))
        {
            $this->set_result_value('runways_snoclo', TRUE);
        }
        else
        {
            $observed = array
            (
                'runway'          => $found[3], // just runway number
                'deposits'        => NULL,
                'deposits_extent' => NULL,
                'deposits_depth'  => NULL,
                'friction'        => NULL,
                'report'          => NULL,
            );

            // Contamination has disappeared (runway has been cleared)
            if (isset($found[4]) AND $found[4] == 'CLRD')
            {
                $observed['deposits'] = 0; // cleared
            }
            // Deposits observed
            else
            {
                // Type
                $deposits = $found[5];

                if (isset($this->runway_deposits_codes[$deposits]))
                {
                    $observed['deposits'] = $deposits;
                }

                // Extent
                $deposits_extent = $found[6];

                if (isset($this->runway_deposits_extent_codes[$deposits_extent]))
                {
                    $observed['deposits_extent'] = $deposits_extent;
                }

                // Depth
                $deposits_depth = $found[7];

                // Uses in mm
                if (intval($deposits_depth) >= 1 AND intval($deposits_depth) <= 90)
                {
                    $observed['deposits_depth'] = intval($deposits_depth);
                }
                // Uses codes
                elseif (isset($this->runway_deposits_depth_codes[$deposits_depth]))
                {
                    $observed['deposits_depth'] = $deposits_depth;
                }
            }

            // Friction observed
            $friction = $found[8];

            // Uses coefficient
            if (intval($friction) > 0 AND intval($friction) <= 90)
            {
                $observed['friction'] = round($friction / 100, 2);
            }
            // Uses codes
            elseif (isset($this->runway_friction_codes[$friction]))
            {
                $observed['friction'] = $friction;
            }

            // Build runways report
            $report = array();

            if (!is_null($observed['deposits']))
            {
                $report[] = $this->runway_deposits_codes[$observed['deposits']];

                if (!is_null($observed['deposits_extent']))
                {
                    $report[] = 'contamination '.$this->runway_deposits_extent_codes[$observed['deposits_extent']];
                }

                if (!is_null($observed['deposits_depth']))
                {
                    if ($observed['deposits_depth'] == '99')
                    {
                        $report[] = 'runway closed';
                    }
                    elseif (isset($this->runway_deposits_depth_codes[$observed['deposits_depth']]))
                    {
                        $report[] = 'deposit is '.$this->runway_deposits_depth_codes[$observed['deposits_depth']].' deep';
                    }
                    else
                    {
                        $report[] = 'deposit is '.$observed['deposits_depth'].' mm deep';
                    }
                }
            }

            if (!is_null($observed['friction']))
            {
                if (isset($this->runway_friction_codes[$observed['friction']]))
                {
                    $report[] = 'a braking action is '.$this->runway_friction_codes[$observed['friction']];
                }
                else
                {
                    $report[] = 'a friction coefficient is '.$observed['friction'];
                }
            }

            $observed['report'] = ucfirst(implode(', ', $report));

            $this->set_result_group('runways_report', $observed);
        }

        return TRUE;
    }

    /**
     * Decodes wind shear information if present.
     * Format is 'WS ALL RWY' or 'WS RWYdd' where dd = Runway designator (see get_runway_vr above).
     */
    private function get_wind_shear($part)
    {
        if ($part != 'WS')
        {
            return FALSE;
        }

        $this->set_result_value('wind_shear_all_runways', FALSE, TRUE);

        $this->part++; // skip this part with WS

        // See two next parts for 'ALL RWY' records
        if (implode(' ', array_slice($this->raw_parts, $this->part, 2)) == 'ALL RWY')
        {
            $this->set_result_value('wind_shear_all_runways', TRUE);

            $this->part += 2; // can skip neext parts with ALL and RWY records
        }
        // See one next part for RWYdd record
        elseif (isset($this->raw_parts[$this->part]))
        {
            $part = $this->raw_parts[$this->part];

            if (!preg_match('@^R(WY)?([0-9]{2}[LCR]?)$@', $part, $found))
            {
                return FALSE;
            }

            if (intval($found[2]) > 36 OR intval($found[2]) < 1)
            {
                return FALSE;
            }

            $this->set_result_group('wind_shear_runways', $found[2]);
        }
        else
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Decodes max and min temperature forecast information if present.
     * Format TXTtTt/ddHHZ or TNTtTt/ddHHZ, where:
     * TX   - Indicator for Maximum temperature
     * TN   - Indicator for Minimum temperature
     * TtTt - Temperature value in Celsius
     * dd   - Forecast day of month
     * HH   - Forecast hour, i.e. the time(hour) when the temperature is expected
     * Z    - Time Zone indicator, Z=GMT.
     */
    private function get_forecast_temperature($part)
    {
        if (!preg_match('@^(TX|TN)(M?[0-9]{2})/([0-9]{2})?([0-9]{2})Z$@', $this->raw_parts[$this->part], $found))
        {
            return FALSE;
        }

        // Temperature
        $temperature_c = intval(strtr($found[2], 'M', '-'));
        $temperature_f = round(1.8 * $temperature_c + 32);

        $forecast = array
        (
            'value'   => $temperature_c,
            'value_f' => $temperature_f,
            'day'     => NULL,
            'time'    => NULL,
        );

        if (!empty($found[3]))
        {
            $forecast['day']  = intval($found[3]);
        }

        $forecast['time'] = $found[4].':00 UTC';

        $parameter = 'forecast_temperature_max';

        if ($found[1] == 'TN')
        {
            $parameter = 'forecast_temperature_min';
        }

        $this->set_result_group($parameter, $forecast);

        return TRUE;
    }

    /**
     * Decodes trends information if present.
     * All METAR trend and TAF records is beginning at: NOSIG, BECMG, TEMP, ATDDhhmm, FMDDhhmm,
     * LTDDhhmm or DDhh/DDhh, where hh = hours, mm = minutes, DD = day of month.
     */
    private function get_trends($part)
    {
        $regexp = '@^((NOSIG|BECMG|TEMPO|INTER|CNL|NIL|PROV|(PROB)([0-9]{2})|(AT|FM|TL)([0-9]{2})?([0-9]{2})([0-9]{2}))|(([0-9]{2})([0-9]{2}))/(([0-9]{2})([0-9]{2})))$@';

        if (!preg_match($regexp, $part, $found))
        {
            return FALSE;
        }

        // Detects TAF on report
        if ($this->part <= 4)
        {
            $this->set_result_value('taf', TRUE);
        }

        // Nil significant changes, skip trend
        if ($found[2] == 'NOSIG')
        {
            return TRUE;
        }

        $trend  = array
        (
            'flag'          => NULL,
            'probability'   => NULL,
            'period'        => array
            (
                'flag'      => NULL,
                'day'       => NULL,
                'time'      => NULL,
                'from_day'  => NULL,
                'from_time' => NULL,
                'to_day'    => NULL,
                'to_time'   => NULL,
            ),
            'period_report' => NULL,
        );

        $raw_parts = array();

        // Get all parts after trend part
        while ($this->part < sizeof($this->raw_parts))
        {
            if (preg_match($regexp, $this->raw_parts[$this->part], $found))
            {
                // Get trend flag
                if (isset($found[2]) AND isset($this->trends_flag_codes[$found[2]]))
                {
                    $trend['flag'] = $found[2];
                }
                // Get PROBpp formatted period
                elseif (isset($found[3]) AND $found[3] == 'PROB')
                {
                    $trend['probability'] = $found[4];
                }
                // Get AT, FM, TL formatted period
                elseif (isset($found[8]) AND isset($this->trends_time_codes[$found[5]]))
                {
                    $trend['period']['flag'] = $found[5];

                    if (!empty($found[6]))
                    {
                        $trend['period']['day']  = intval($found[6]);
                    }

                    $trend['period']['time'] = $found[7].':'.$found[8].' UTC';
                }
                // Get DDhh/DDhh formatted period
                elseif (isset($found[14]))
                {
                    $trend['period']['from_day']  = $found[10];
                    $trend['period']['from_time'] = $found[11].':00 UTC';
                    $trend['period']['to_day']    = $found[13];
                    $trend['period']['to_time']   = $found[14].':00 UTC';
                }
            }
            // If RMK observed -- the trend is ended
            elseif ($this->raw_parts[$this->part] == 'RMK')
            {
                if (!empty($raw_parts))
                {
                    $this->part--; // return pointer to RMK part
                }

                break;
            }
            // Other data addrs to METAR raw
            else
            {
                $raw_parts[] = $this->raw_parts[$this->part];
            }

            $this->part++; // go to next part

            // Detect ends of this trend, if the METAR raw data observed
            if (!empty($raw_parts))
            {
                if (!isset($this->raw_parts[$this->part]) OR preg_match($regexp, $this->raw_parts[$this->part]))
                {
                    $this->part--; // return pointer to finded part

                    break;
                }
            }
        }

        // Empty trend is a bad trend, except for flags CNL and NIL
        if (empty($raw_parts))
        {
            if ($trend['flag'] != 'CNL' AND $trend['flag'] != 'NIL')
            {
                $this->part--; // return pointer to previous part

                return FALSE;
            }
        }
        // Parse raw data from trend
        else
        {
            $class  = __CLASS__;
            $parser = new $class(implode(' ', $raw_parts), TRUE, $this->debug_enabled, FALSE);

            if ($parsed = $parser->parse())
            {
                unset($parsed['taf']);

                // Add parsed data to trend
                if (!empty($parsed))
                {
                    $trend = array_merge($trend, $parsed);
                }
            }

            // Process debug messages
            if ($debug = $parser->debug())
            {
                foreach ($debug as $message)
                {
                    $this->set_debug('Recursion: '.$message);
                }
            }

            // Process parse errors
            if ($errors = $parser->errors())
            {
                foreach ($errors as $message)
                {
                    $this->set_error('Recursion: '.$message);
                }
            }
        }

        // Build the report
        $report = array();

        if (!is_null($trend['flag']))
        {
            $report[] = $this->trends_flag_codes[$trend['flag']];
        }

        if (!is_null($trend['period']['flag']))
        {
            if (!is_null($trend['period']['day']))
            {
                $report[] = $this->trends_time_codes[$trend['period']['flag']].
                            ' a '.$trend['period']['day'].' day of the month on '.$trend['period']['time'];
            }
            else
            {
                $report[] = $this->trends_time_codes[$trend['period']['flag']].' '.$trend['period']['time'];
            }
        }

        if (!is_null($trend['period']['from_day']) AND !is_null($trend['period']['to_day']))
        {
            $report[] = 'from a '.$trend['period']['from_day'].' day of the month on '.$trend['period']['from_time'];
            $report[] = 'to a '.$trend['period']['to_day'].' day of the month on '.$trend['period']['to_time'];
        }

        if (!is_null($trend['probability']))
        {
            $report[] = 'probability '.$trend['probability'].'% of the conditions existing';
        }

        if (!empty($report))
        {
            $trend['period_report'] = ucfirst(implode(', ', $report));
        }

        $this->set_result_group('trends', $trend);

        return TRUE;
    }

    /**
     * Get remarks information if present.
     * The information is everything that comes after RMK.
     */
    private function get_remarks($part)
    {
        if ($part != 'RMK')
        {
            return FALSE;
        }

        $this->part++; // skip this part with RMK

        $remarks = array();

        // Get all parts after
        while ($this->part < sizeof($this->raw_parts))
        {
            if (isset($this->raw_parts[$this->part]))
            {
                $remarks[] = $this->raw_parts[$this->part];
            }

            $this->part++; // go to next part
        }

        if (!empty($remarks))
        {
            $this->set_result_value('remarks', implode(' ', $remarks));
        }

        $this->method++;

        return TRUE;
    }

    // --------------------------------------------------------------------
    // Other methods
    // --------------------------------------------------------------------

    /**
     * Decodes present or recent weather conditions.
     */
    private function decode_weather($part, $method, $regexp_prefix = '')
    {
        $wx_codes = implode('|', array_keys(array_merge($this->weather_char_codes, $this->weather_type_codes)));

        if (!preg_match('@^'.$regexp_prefix.'([-+]|VC)?('.$wx_codes.')?('.$wx_codes.')?('.$wx_codes.')?('.$wx_codes.')@', $part, $found))
        {
            return FALSE;
        }

        $observed = array
        (
            'intensity'       => NULL,
            'types'           => NULL,
            'characteristics' => NULL,
            'report'          => NULL,
        );

        // Intensity
        if ($found[1] != NULL)
        {
            $observed['intensity'] = $found[1];
        }

        foreach (array_slice($found, 1) as $code)
        {
            // Types
            if (isset($this->weather_type_codes[$code]))
            {
                if (is_null($observed['types']))
                {
                    $observed['types'] = array();
                }

                $observed['types'][] = $code;
            }

            // Characteristics (uses last)
            if (isset($this->weather_char_codes[$code]))
            {
                $observed['characteristics'] = $code;
            }
        }

        // Build recent weather report
        if (!is_null($observed['characteristics']) OR !is_null($observed['types']))
        {
            $report = array();

            if (!is_null($observed['intensity']))
            {
                if ($observed['intensity'] == 'VC')
                {
                    $report[] = $this->weather_intensity_codes[$observed['intensity']].',';
                }
                else
                {
                    $report[] = $this->weather_intensity_codes[$observed['intensity']];
                }
            }

            if (!is_null($observed['characteristics']))
            {
                $report[] = $this->weather_char_codes[$observed['characteristics']];
            }

            if (!is_null($observed['types']))
            {
                foreach ($observed['types'] as $code)
                {
                    $report[] = $this->weather_type_codes[$code];
                }
            }

            $report = implode(' ', $report);

            $observed['report'] = ucfirst($report);

            $this->set_result_report($method.'_weather_report', $report);
        }

        $this->set_result_group($method.'_weather', $observed);

        return TRUE;
    }

    /**
     * Calculate Heat Index based on temperature in F and relative humidity (65 = 65%)
     */
    private function calculate_heat_index($temperature_f, $rh)
    {
        if ($temperature_f > 79 AND $rh > 39)
        {
            $hi_f  = -42.379 + 2.04901523 * $temperature_f + 10.14333127 * $rh - 0.22475541 * $temperature_f * $rh;
            $hi_f += -0.00683783 * pow($temperature_f, 2) - 0.05481717 * pow($rh, 2);
            $hi_f +=  0.00122874 * pow($temperature_f, 2) * $rh + 0.00085282 * $temperature_f * pow($rh, 2);
            $hi_f += -0.00000199 * pow($temperature_f, 2) * pow($rh, 2);
            $hi_f  = round($hi_f);
            $hi_c  = round(($hi_f - 32) / 1.8);

            $this->set_result_value('heat_index', $hi_c);
            $this->set_result_value('heat_index_f', $hi_f);
        }
    }

    /**
     * Calculate Wind Chill Temperature based on temperature in F
     * and wind speed in miles per hour.
     */
    private function calculate_wind_chill($temperature_f)
    {
        if ($temperature_f < 51 AND $this->result['wind_speed'] != 0)
        {
            $windspeed = round(2.23694 * $this->result['wind_speed']); // convert m/s to mi/h

            if ($windspeed > 3)
            {
                $chill_f  = 35.74 + 0.6215 * $temperature_f - 35.75 * pow($windspeed, 0.16);
                $chill_f += 0.4275 * $temperature_f * pow($windspeed, 0.16);
                $chill_f  = round($chill_f);
                $chill_c  = round(($chill_f - 32) / 1.8);

                $this->set_result_value('wind_chill', $chill_c);
                $this->set_result_value('wind_chill_f', $chill_f);
            }
        }
    }

    /**
     * Convert wind speed into meters per second.
     * Some other common conversion factors:
     *   1 mi/hr = 0.868976 knots  = 0.000447 km/hr = 0.44704  m/s  = 1.466667 ft/s
     *   1 ft/s  = 0.592483 knots  = 1.097279 km/hr = 0.304799 m/s  = 0.681818 mi/hr
     *   1 knot  = 1.852    km/hr  = 0.514444 m/s   = 1.687809 ft/s = 1.150779 mi/hr
     *   1 km/hr = 0.539957 knots  = 0.277778 m/s   = 0.911344 ft/s = 0.621371 mi/hr
     *   1 m/s   = 1.943844 knots  = 3.6      km/h  = 3.28084  ft/s = 2.236936 mi/hr
     */
    private function convert_speed($speed, $unit)
    {
        switch ($unit)
        {
            case 'KT':
                return round(0.514444 * $speed, 2); // from knots

            case 'KPH':
                return round(0.277778 * $speed, 2); // from km/h

            case 'MPS':
                return round($speed, 2); // m/s
        }

        return NULL;
    }

    /**
     * Convert distance into meters.
     * Some other common conversion factors:
     *   1 m  = 3.28084 ft = 0.00062 mi
     *   1 ft = 0.3048 m   = 0.00019 mi
     *   1 mi = 5279.99 ft = 1609.34 m
     */
    private function convert_distance($distance, $unit)
    {
        switch ($unit)
        {
            case 'FT':
                return round(0.3048 * $distance); // from ft.

            case 'SM':
                return round(1609.34 * $distance); // from miles

            case 'M':
                return round($distance); // meters
        }

        return NULL;
    }

    /**
     * Convert direction degrees to compass label.
     */
    private function convert_direction_label($direction)
    {
        if ($direction >= 0 AND $direction <= 360)
        {
            return $this->direction_codes[round($direction / 22.5) % 16];
        }

        return NULL;
    }
}

/* End of File */
