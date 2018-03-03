<?php

namespace TransIP\Tests\ApiCall;

use PHPUnit\Framework\TestCase;
use TransIP\TransIPClient;

class ServerTest extends TestCase
{
    /**
     * @var TransIPClient
     */
    protected $client;

    /**
     * @var JsonData
     */
    protected $jsonData;

    protected function setUp()
    {
        $adapterClass = 'TransIP\Adapter\\' . getenv('ADAPTER');

        $adapter = new $adapterClass(getenv('ACCESSTOKEN'));
        $adapter->setEndpoint(getenv('ENDPOINT'));

        $this->client = new TransIPClient($adapter);
    }

    /** @test */
    public function get_all_the_products()
    {
        $result = $this->client->vps()->products();

        $this->assertObjectHasAttribute('vps', $result);
        $this->assertObjectHasAttribute('vpsAddon', $result);
        $this->assertObjectHasAttribute('haip', $result);
        $this->assertObjectHasAttribute('bigStorage', $result);
        $this->assertObjectHasAttribute('privateNetworks', $result);
    }

    /** @test */
    public function get_all_the_vpses()
    {
        $result = $this->client->vps()->vps();

        $this->assertObjectHasAttribute('vpses', $result);
    }

    /** @test */
    public function get_vps_info()
    {
        $result = $this->client->vps()->vpsInfo('example-vps');

        $this->assertObjectHasAttribute('vps', $result);
        $this->assertEquals('example-vps', $result->vps->name);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function get_error_when_vps_not_found()
    {
        $result = $this->client->vps()->vpsInfo('vps-not-found');
    }

    /** @test */
    public function can_order_a_vps()
    {
        $result = $this->client->vps()->order('vps-bladevps-x8', 'ubuntu-16.04', ['vpsAddon-1-extra-cpu-core']);

    }
}
