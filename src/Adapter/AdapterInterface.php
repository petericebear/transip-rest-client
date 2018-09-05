<?php

namespace TransIP\Adapter;

interface AdapterInterface
{
    /**
     * GET Method
     *
     * @param string $url  API method to call
     * @param array  $args Argument to pas along with the method call.
     *
     * @return array
     */
    public function get($url, array $args = []);

    /**
     * PATCH Method
     *
     * @param string  $url     API method to call
     * @param array   $args    Argument to pas along with the method call.
     * @param boolean $getCode whether or not to return the HTTP response code.
     *
     * @return array|integer when $getCode is set, the HTTP response code will
     * be returned, otherwise an array of results will be returned.
     */
    public function patch($url, array $args, $getCode = true);

    /**
     * POST Method
     *
     * @param string  $url     API method to call
     * @param array   $args    Argument to pas along with the method call.
     * @param boolean $getCode whether or not to return the HTTP response code.
     *
     * @return array|integer when $getCode is set, the HTTP response code will
     * be returned, otherwise an array of results will be returned.
     */
    public function post($url, array $args, $getCode = false);

    /**
     * PUT Method
     *
     * @param string  $url     API method to call
     * @param array   $args    Argument to pas along with the method call.
     * @param boolean $getCode whether or not to return the HTTP response code.
     *
     * @return array|integer when $getCode is set, the HTTP response code will
     * be returned, otherwise an array of results will be returned.
     */
    public function put($url, array $args, $getCode = true);

    /**
     * DELETE Method
     *
     * @param string $url  API method to call
     * @param array  $args Argument to pas along with the method call.
     *
     * @return array
     */
//    public function delete($url, array $args = []);


    /**
     * Added primarily to allow proper code testing.
     *
     * @param string $endpoint
     */
    public function setEndpoint($endpoint);
}
