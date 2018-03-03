<?php

namespace TransIP\Tests;

use PHPUnit\Framework\TestCase;
use TransIP\TransIPClient;

class TransIPClientTest extends TestCase
{
    public function testConstruct()
    {
        $adapterClass = 'TransIP\Adapter\\' . getenv('ADAPTER');

        $adapter = new $adapterClass(getenv('ACCESSTOKEN'));
        $adapter->setEndpoint(getenv('ENDPOINT'));

        $client = new TransIPClient($adapter);

        $this->assertInstanceOf('TransIP\TransIPClient', $client);
    }

    /**
     * @expectedException \TypeError
     */
    public function testConstructException()
    {
        $mockAdapter = $stub = $this->getMockBuilder('NonExistentAdapter')
            ->getMock();

        $this->client = new TransIPClient($mockAdapter);
    }
}
