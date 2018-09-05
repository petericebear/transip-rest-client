<?php

namespace TransIP\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use TransIP\Exception\ApiException;
use TransIP\TransIPClient;

class GuzzleHttpAdapter extends AbstractAdapter
{
    /**
     * Access Token
     *
     * @see https://www.transip.nl/cp/account/api/
     *
     * @var string $accessToken TransIP.nl Access token
     */
    protected $accessToken;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $postOptions;

    /**
     * @var integer
     */
    protected $guzzleVersion;

    /**
     * @param string $apiToken Vultr API token
     *
     * @throws \RuntimeException
     */
    public function __construct($accessToken)
    {
        if (version_compare(ClientInterface::VERSION, '6') === 1) {
            $this->guzzleVersion = 6;
            $this->postOptions = 'body';
        } elseif (version_compare(ClientInterface::VERSION, '5') === 1) {
            $this->guzzleVersion = 5;
            $this->postOptions = 'body';
        } else {
            throw new \RuntimeException('Unsupported guzzle version! Install guzzle 5 or 6.');
        }

        $this->accessToken = $accessToken;
        $this->buildClient();
    }

    /**
     * Helper function to build the Guzzle HTTP client.
     *
     * @param string $endpoint API endpoint
     */
    protected function buildClient($endpoint = null)
    {
        if ($endpoint === null) {
            $endpoint = TransIPClient::ENDPOINT;
        }

        $config = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->accessToken,
                'User-Agent' => sprintf('%s v%s (%s)',
                    TransIPClient::AGENT,
                    TransIPClient::VERSION,
                    'https://github.com/petericebear/transip-rest-client'
                ),
            ],
        ];

        switch ($this->guzzleVersion) {
            case 5:
                $config = [
                    'base_url' => $endpoint,
                    'defaults' => $config,
                ];
                break;
            case 6:
                $config['base_uri'] = $endpoint;
                break;
        }

        $this->client = new Client($config);
    }

    /**
     * {@inheritdoc}
     */
    public function setEndpoint($endpoint)
    {
        $this->buildClient($endpoint);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $args, $getCode = false)
    {
        $options[$this->postOptions] = json_encode($args);

        try {
            $this->response = $this->client->delete($url, $options);

            if ($getCode) {
                return (int) $this->response->getStatusCode();
            }
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            return $this->handleError($getCode);
        }

        return json_decode($this->response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function get($url, array $args = [])
    {
        $options = [];

        // Add additional arguments to the defaults:
        //   Guzzle 6 does no longer merge the default query params with the
        //   additional params given here!
        if (!empty($args)) {
            if ($this->guzzleVersion > 5) {
                $options['query'] = array_merge(
                    $this->client->getConfig('query'),
                    $args
                );
            } else {
                $options['query'] = $args;
            }
        }

        try {
            $this->response = $this->client->get($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            return $this->handleError();
        }

        // $response->json() is not compatible with Guzzle 6.
        return json_decode($this->response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function patch($url, array $args, $getCode = false)
    {
        $options[$this->postOptions] = json_encode($args);

        try {
            $this->response = $this->client->patch($url, $options);

            if ($getCode) {
                return (int) $this->response->getStatusCode();
            }
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            return $this->handleError($getCode);
        }

        return json_decode($this->response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, array $args, $getCode = false)
    {
        $options[$this->postOptions] = json_encode($args);

        try {
            $this->response = $this->client->post($url, $options);

            if ($getCode) {
                return (int) $this->response->getStatusCode();
            }
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            return $this->handleError($getCode);
        }

        return json_decode($this->response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $args, $getCode = false)
    {
        $options[$this->postOptions] = json_encode($args);

        try {
            $this->response = $this->client->put($url, $options);

            if ($getCode) {
                return (int) $this->response->getStatusCode();
            }
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            return $this->handleError($getCode);
        }

        return json_decode($this->response->getBody());
    }

    /**
     * @param bool $getCode
     * @return int
     *
     * @throws ApiException
     */
    protected function handleError($getCode = false)
    {
        $code = (int) $this->response->getStatusCode();

        $content = (string) $this->response->getBody();
        $this->reportError($code, $content);

        if ($getCode) {
            return $code;
        }
    }
}
