<?php

namespace TransIP\Tests;

use PHPUnit\Framework\TestCase;
use TransIP\Adapter\CurlAdapter;

class CurlAdapterTest extends TestCase
{
    /**
     * @var CurlAdapter;
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new CurlAdapter('EXAMPLE');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('TransIP\Adapter\CurlAdapter', $this->client);
    }
}
