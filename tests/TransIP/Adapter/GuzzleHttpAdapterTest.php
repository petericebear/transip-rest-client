<?php

namespace TransIP\Tests;

use PHPUnit\Framework\TestCase;
use TransIP\Adapter\GuzzleHttpAdapter;

class GuzzleHttpAdapterTest extends TestCase
{
    /**
     * @var GuzzleHttpAdapter;
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttpAdapter('EXAMPLE');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('TransIP\Adapter\GuzzleHttpAdapter', $this->client);
    }
}
