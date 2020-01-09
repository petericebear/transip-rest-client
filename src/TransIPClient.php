<?php

namespace TransIP;

use TransIP\Adapter\AdapterInterface;
use TransIP\ApiCall\HaipService;
use TransIP\ApiCall\VpsService;

class TransIPClient
{
    const ENDPOINT = 'https://api.transip.nl/v6/';
    const VERSION  = '1.0';
    const AGENT    = 'TransIP.nl REST API Client';

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Provides VPS related calls.
     *
     * @return VpsService
     */
    public function vps()
    {
        return new VpsService($this->adapter);
    }

    /**
     * Provides HA-IP related calls.
     *
     * @return HaipService
     */
    public function haip()
    {
        return new HaipService($this->adapter);
    }
}
