<?php

namespace TransIP\ApiCall;

class VpsService extends AbstractApiCall
{
    /**
     * @var array $actions Available actions to trigger for VPS.
     */
    protected $actions = [
        'start',
        'stop',
        'reset',
    ];

    /**
     * @var array $endTimes When to delete the VPS? Immediately or at the end of current Period?
     */
    protected $endTimes = [
        'end',
        'immediately',
    ];

    public function action($name, $action)
    {
        if (!in_array($action, $this->actions)) {
            $this->adapter->reportError(406, 'Not an available action given.');
        }

        $args = [
            'action' => $action,
        ];

        return $this->adapter->patch('vps/' . $name, $args, true);
    }

    public function availabilityZones()
    {
        return $this->adapter->get('availability-zone');
    }

    public function availabilityZone($zone)
    {
        return $this->adapter->get('availability-zone/' . $zone);
    }

    public function cancel($name, $endTime = 'end')
    {
        if (!in_array($endTime, $this->endTimes)) {
            $this->adapter->reportError(406, 'Not an available endTime given.');
        }

        $args = [
            'endTime' => $endTime,
        ];

        return $this->adapter->delete('vps/' . $name, $args, true);
    }

    public function changeDescription($name, $description)
    {
        $details = $this->vps($name);
        $details->vps->description = $description;

        $args = [
            'vps' => $details,
        ];

        return $this->adapter->put('vps/' . $name, $args, true);
    }

    public function cloneVps($name, $availabilityZone)
    {
        $args = [
            'vpsName' => $name,
            'availabilityZone' => $availabilityZone,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function lock($name)
    {
        $details = $this->vps($name);
        $details->vps->isCustomerLocked = true;

        $args = [
            'vps' => $details,
        ];

        return $this->adapter->put('vps/' . $name, $args, true);
    }

    public function order($productName, $operatingSystem, $availabilityZone, array $addons = [], $hostname = '', $description = '')
    {
        $args = [
            'productName' => $productName,
            'operatingSystem' => $operatingSystem,
            'availabilityZone' => $availabilityZone,
            'addons' => $addons,
            'hostname' => $hostname,
            'description' => $description,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function orderMultiple(array $vpss)
    {
        $args = [
            'vpss' => $vpss,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function products()
    {
        return $this->adapter->get('products');
    }

    public function unlock($name)
    {
        $details = $this->vps($name);
        $details->vps->isCustomerLocked = false;

        $args = [
            'vps' => $details,
        ];

        return $this->adapter->put('vps/' . $name, $args, true);
    }

    public function update($name, array $changes = [])
    {
        $details = $this->vps($name);

        foreach ($changes as $key => $value) {
            $details->vps->{$key} = $value;
        }

        $args = [
            'vps' => $details,
        ];

        return $this->adapter->put('vps/' . $name, $args, true);
    }

    public function vps($name)
    {
        return $this->adapter->get('vps/' . $name);
    }

    public function vpsList()
    {
        return $this->adapter->get('vps');
    }

    public function traffic($name)
    {
        return $this->adapter->get('traffic/' . $name);
    }

    public function trafficTotal()
    {
        return $this->adapter->get('traffic');
    }
}
