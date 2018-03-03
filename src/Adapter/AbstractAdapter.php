<?php

namespace TransIP\Adapter;

use TransIP\Exception\ApiException;

abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Throw error with explanation of what happened.
     *
     * @param integer $code
     * @param string $response
     *
     * @throws ApiException
     */
    public function reportError($code, $response) {
        switch($code) {
            case 400:
                throw new ApiException('Bad request. The API version or URL is invalid.');
                break;
            case 401:
                throw new ApiException('Unauthorized. Check the API version or URL is invalid.');
                break;
            case 403:
                throw new ApiException('Forbidden. You don’t have the necessary permissions to perform an operation.');
                break;
            case 404:
                throw new ApiException('Not Found. Resource was not found.');
                break;
            case 405:
                throw new ApiException('Method Not Allowed. You’re using an HTTP method on a resource which does not support it.');
                break;
            case 406:
                throw new ApiException('Not Acceptable. One or more required parameters are missing or not correct in the request.');
                break;
            case 408:
                throw new ApiException('Request Timeout. The request got a timeout.');
                break;
            case 409:
                throw new ApiException('Conflict. Modification is not permitted at this moment.');
                break;
            case 422:
                throw new ApiException('Unprocessable Entity. The input attributes are invalid, e.g. malformed JSON.');
                break;
            case 429:
                throw new ApiException('Too Many Request. The rate limit is exceeded.');
                break;
            case 500:
                throw new ApiException('Internal server error. Try again at a later time.');
                break;
            case 501:
                throw new ApiException('Not Implemented. The endpoint is not implemented.');
                break;
        }
    }
}
