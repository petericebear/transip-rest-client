<?php

namespace TransIP\Adapter;

use TransIP\Exception\ApiException;
use TransIP\TransIPClient;

class CurlAdapter extends AbstractAdapter
{
    /**
     * @var string API endpoint
     */
    protected $endpoint;

    /**
     * Access Token
     *
     * @see https://www.transip.nl/cp/account/api/
     *
     * @var string $accessToken TransIP.nl Access token
     */
    protected $accessToken;

    /**
     * The API responsecode
     *
     * @var integer
     */
    protected $responseCode;

    /**
     * Debug Variable
     *
     * @var bool Debug API requests
     */
    protected $debug;

    /**
     * Constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->responseCode = 0;
        $this->debug = true;

        $this->endpoint = TransIPClient::ENDPOINT;
    }

    /**
     * Enable debug mode.
     *
     * @param boolean $debug
     *
     * @return self
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Added primarily to allow proper code testing.
     *
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url, array $args = [])
    {
        return $this->query($url, $args, 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $args, $getCode = false)
    {
        return $this->query($url, $args, 'POST', $getCode);
    }

    /**
     * API Query Function
     *
     * @param string  $url
     * @param array   $args
     * @param string  $requestType POST|GET
     * @param boolean $getCode     whether or not to return the HTTP response code
     *
     * @return object|integer
     */
    protected function query($url, array $args, $requestType, $getCode = false)
    {
        $url = $this->endpoint . $url;

        if ($this->debug) {
            print($requestType . ' ' . $url . PHP_EOL);
        }

        $defaults = [
            CURLOPT_USERAGENT => sprintf('%s v%s (%s)', TransIPClient::AGENT, TransIPClient::VERSION, 'https://github.com/petericebear/transip-rest-client'),
            CURLOPT_HEADER => 0,
            CURLOPT_VERBOSE => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTP_VERSION => '1.0',
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Authorization: Bearer '.$this->accessToken],
        ];

        switch ($requestType) {
            case 'POST':
                $postData = http_build_query($args);
                $defaults[CURLOPT_URL] = $url;
                $defaults[CURLOPT_POST] = 1;
                $defaults[CURLOPT_POSTFIELDS] = json_encode($postData);
                break;

            case 'GET':
                if (count($args)) {
                    $getData = http_build_query($args);
                    $defaults[CURLOPT_URL] = $url . '&' . $getData;
                } else {
                    $defaults[CURLOPT_URL] = $url;
                }
                break;
        }

        $apisess = curl_init();
        curl_setopt_array($apisess, $defaults);
        $response = curl_exec($apisess);

        // Check to see if there were any API exceptions thrown.
        // If so, then error out, otherwise, keep going.
        $this->isAPIError($apisess, $response, $getCode);
        // The call above also closes the curl

        if ($getCode) {
            return (int) $this->responseCode;
        }

        // Return the decoded JSON response.
        $array = json_decode($response);

        return $array;
    }

    /**
     * @param $responseObj
     * @param $response
     * @param $getCode
     *
     * @throws ApiException
     */
    protected function isAPIError($responseObj, $response, $getCode)
    {
        $code = curl_getinfo($responseObj, CURLINFO_HTTP_CODE);
        curl_close($responseObj);

        if ($getCode) {
            $this->responseCode = $code;
            return;
        }

        if ($this->debug) {
            echo $code . PHP_EOL;
        }

        $this->reportError($code, $response);
    }
}
