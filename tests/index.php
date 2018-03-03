<?php

/**
 * TransIP.nl PHP REST Client
 *
 * Dummy API endpoint to allow actual adapter tests but with dummy data.
 *
 * @package TransIP
 * @version 1.0
 * @author  https://github.com/petericebear
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @see     https://github.com/petericebear/transip-rest-client
 */

require_once 'JsonData.php';

// Our fake 'content store'.
$jsonData = new \TransIP\Tests\JsonData();

// Grab requested url.
$url = ltrim($_SERVER['PATH_INFO'], '/');

// Prepare arguments.
$args = $_GET;
unset($args['accesstoken']);

// Return fake data.
header('Content-Type: application/json');
print(
    $jsonData->getResponse($url, $args)
);
