<?php

/**
 * egyApiNHK
 * http://github.com/egy186/egy_Api_NHK
 *
 * PHP Library for NHK番組表API
 *
 * Copyright (c) 2013 egy186
 * Released under the MIT license.
 */

namespace egy\Api;

/**
 * egy\Api\NHK
 */
class NHK {
//region Settings
    // Request parameters
    private $req_params = array();
    // Contains information
    private $egy_info = array();
    // Base URL for v1
    public $base_URL='http://api.nhk.or.jp/v1/';
    // Program List URL
    public function programListURL() { return $this->base_URL . 'pg/list/'; }
    // Program Genre URL
    public function programGenreURL() { return $this->base_URL . 'pg/genre/'; }
    // Program Info URL
    public function programInfoURL() { return $this->base_URL . 'pg/info/'; }
    // Now On Air URL
    public function nowOnAirURL() { return $this->base_URL . 'pg/now/'; }
//endregion

//region cURL Settings
    // Connect timeout
    public $curl_connecttimeout = 30;
    // Timeout
    public $curl_timeout = 30;
    // User agent
    private $curl_user_agent = 'egyApiNHK v1.0.0a';
    // Verify SSL
    public $curl_ssl_verifypeer = false;
//endregion

//region Construct
    /**
     * Construct egy\Api\NHK
     *
     * @param string $apikey your apikey (32byte)
     */
    public function __construct($apikey) {
        $this->setApikey($apikey);
    }
    
    /**
     * Set apikey
     *
     * @param string $apikey your apikey (32byte)
     */
    public function setApikey($apikey) {
        $this->req_params['key'] = $apikey;
    }
//endregion

//region NHK APIs
    /**
     * Program List API
     *
     * @param  string $area     Area ID (3byte)
     * @param  string $service  Service ID (2byte)
     * @param  string $date     Date (YYYY-MM-DD)
     * @return string $response List (json)
     */
    public function programListAPI($area, $service, $date) {
        $method = 'GET';
        $url = $this->programListURL() . $area . '/' . $service . '/' . $date . '.json';
        return $this->cURLRequest($method, $url, $this->req_params);
    }

    /**
     * Program Genre API
     *
     * @param  string $area     Area ID (3byte)
     * @param  string $genre    Genre ID (4byte)
     * @param  string $date     Date (YYYY-MM-DD)
     * @return string $response List (json)
     */
    public function programGenreAPI($area, $genre, $date) {
        $method = 'GET';
        $url = $this->programGenreURL() . $area . '/' . $genre . '/' . $date . '.json';
        return $this->cURLRequest($method, $url, $this->req_params);
    }

    /**
     * Program Info API
     *
     * @param  string $area     Area ID (3byte)
     * @param  string $id       Program ID (13byte)
     * @return string $response Description (json)
     */
    public function programInfoAPI($area, $id) {
        $method = 'GET';
        $url = $this->programInfoURL() . $area . '/' . $id . '.json';
        return $this->cURLRequest($method, $url, $this->req_params);
    }

    /**
     * Now On Air API
     *
     * @param  string $area     Area ID (3byte)
     * @param  string $service  Service ID (2byte) or 'tv' or 'radio' or 'all'
     * @return string $response NowOnAir or NowOnAirList (json)
     */
    public function nowOnAirAPI($area, $service) {
        $method = 'GET';
        $url = $this->nowOnAirURL() . $area . '/' . $service . '.json';
        return $this->cURLRequest($method, $url, $this->req_params);
    }
//endregion

//region Utilities
    /**
     * Help debugging
     *
     * @param  void
     * @return array
     */
    public function getInfo() {
        return $egy_info;
    }

    /**
     * make cURL request
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $parameters
     * @return string $response
     */
    public function cURLRequest($method, $url, $parameters = array()) {
        $method = strtoupper($method);
        $ch = curl_init();
        curl_setopt_array($ch,
            array(
                CURLOPT_CONNECTTIMEOUT => $this->curl_connecttimeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => $this->curl_ssl_verifypeer,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_TIMEOUT => $this->curl_timeout,
                CURLOPT_USERAGENT => $this->curl_user_agent
            )
        );
        switch($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
                break;
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Length: 0');
                if($parameters) {
                    $url .= '?' . http_build_query($parameters);
                }
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true); //for debug
        $response = curl_exec($ch);

        $this->egy_info['curl_info'] = curl_getinfo($ch);
        curl_close($ch);
        return $response;
    }
//endregion
}
/* EOF */