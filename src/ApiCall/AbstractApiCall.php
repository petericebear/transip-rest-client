<?php

namespace TransIP\ApiCall;

use TransIP\Adapter\AdapterInterface;

abstract class AbstractApiCall
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
