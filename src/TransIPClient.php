<?php

namespace TransIP;

use TransIP\Adapter\AdapterInterface;
use TransIP\ApiCall\VPS;

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
     * @return VPS
     */
    public function vps()
    {
        return new VPS($this->adapter);
    }
}
