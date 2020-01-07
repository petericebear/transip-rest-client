<?php

namespace TransIP\Tests\ApiCall;

use PHPUnit\Framework\TestCase;
use TransIP\TransIPClient;

class VpsServiceTest extends TestCase
{
    /**
     * @var TransIPClient
     */
    protected $client;

    /**
     * @var $jsonData
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
    public function get_all_availability_zones()
    {
        $result = $this->client->vps()->availabilityZones();

        $this->assertObjectHasAttribute('availabilityZones', $result);
    }

    /** @test */
    public function get_all_availability_zone()
    {
        $result = $this->client->vps()->availabilityZone('ams0');

        $this->assertObjectHasAttribute('availabilityZone', $result);
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
        $result = $this->client->vps()->vpsList();

        $this->assertObjectHasAttribute('vpss', $result);
    }

    /** @test */
    public function get_vps_info()
    {
        $result = $this->client->vps()->vps('example-vps');

        $this->assertObjectHasAttribute('vps', $result);
        $this->assertEquals('example-vps', $result->vps->name);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function get_error_when_vps_not_found()
    {
        $this->client->vps()->vps('vps-not-found');
    }

    /** @test */
    public function can_order_a_vps()
    {
        $result = $this->client->vps()->order('vps-bladevps-x4', 'ubuntu-18.04', 'ams0', ['vpsAddon-1-extra-cpu-core']);

        $this->assertInternalType('int', $result);
        $this->assertEquals(201, $result);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function ordering_a_vps_failes_when_hostname_is_not_correct()
    {
        $result = $this->client->vps()->order('vps-bladevps-x4', 'ubuntu-18.04', 'ams0', ['vpsAddon-1-extra-cpu-core'], 'ex@mple-vps');

        $this->assertInternalType('int', $result);
        $this->assertEquals(406, $result);
    }

    /** @test */
    public function can_clone_a_vps()
    {
        $result = $this->client->vps()->cloneVps('example-vps', '');

        $this->assertInternalType('int', $result);
        $this->assertEquals(201, $result);
    }

    /** @test */
    public function can_lock_a_vps()
    {
        $result = $this->client->vps()->update('example-vps', ['isCustomerLocked' => true]);

        $this->assertInternalType('int', $result);
    }

    /** @test */
    public function can_unlock_a_vps()
    {
        $result = $this->client->vps()->update('example-vps', ['isCustomerLocked' => false]);

        $this->assertInternalType('int', $result);
    }

    /** @test */
    public function can_change_description_of_vps()
    {
        $result = $this->client->vps()->update('example-vps', ['description' => 'New description']);

        $this->assertInternalType('int', $result);
    }

    /** @test */
    public function can_start_a_vps()
    {
        $result = $this->client->vps()->action('example-vps', 'start');

        $this->assertInternalType('int', $result);
    }

    /** @test */
    public function can_stop_a_vps()
    {
        $result = $this->client->vps()->action('example-vps', 'stop');

        $this->assertInternalType('int', $result);
    }

    /** @test */
    public function can_reset_a_vps()
    {
        $result = $this->client->vps()->action('example-vps', 'reset');

        $this->assertInternalType('int', $result);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function does_a_check_for_valid_action()
    {
        $this->client->vps()->action('example-vps', 'not-allowed');
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function get_error_when_vps_for_clone_not_found()
    {
        $result = $this->client->vps()->cloneVps('vps-not-found');

        $this->assertInternalType('int', $result);
        $this->assertEquals(404, $result);
    }

    /** @test */
    public function can_delete_a_vps()
    {
        $result = $this->client->vps()->cancel('delete-vps');

        $this->assertInternalType('int', $result);
        $this->assertEquals(204, $result);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function cannot_delete_a_vps_when_incorrect_endtime_is_given()
    {
        $this->client->vps()->cancel('delete-vps', 'not-allowed');
    }

    /** @test */
    public function can_get_traffic_information()
    {
        $result = $this->client->vps()->trafficTotal();

        $this->assertObjectHasAttribute('trafficInformation', $result);
        $this->assertEquals('2017-06-22', $result->trafficInformation->startDate);
    }

    /** @test */
    public function can_get_traffic_information_for_vps()
    {
        $result = $this->client->vps()->traffic('example-vps');

        $this->assertObjectHasAttribute('trafficInformation', $result);
        $this->assertEquals('2017-06-22', $result->trafficInformation->startDate);
    }

    /**
     * @test
     * @expectedException \TransIP\Exception\ApiException
     */
    public function get_error_when_vps_not_found_for_traffic_info()
    {
        $this->client->vps()->traffic('vps-not-found');
    }
}
