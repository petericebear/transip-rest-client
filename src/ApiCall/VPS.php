<?php

namespace TransIP\ApiCall;

class VPS extends AbstractApiCall
{
    public function products()
    {
        return $this->adapter->get('products');
    }

    public function vps()
    {
        return $this->adapter->get('vps');
    }

    public function vpsInfo($name)
    {
        return $this->adapter->get('vps/'.$name);
    }

    public function order($productName, $operatingSystem, array $addons = [], $hostname = '')
    {
        $args = [
            'productName' => $productName,
            'operatingSystem' => $operatingSystem,
            'addons' => $addons,
            'hostname' => $hostname,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function cloneVps($name)
    {
        $args = [
            'vpsName' => $name,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function updateVPS($name, array $changes = [])
    {
        $details = $this->vpsInfo($name);

        foreach ($changes as $key => $value) {
            $details->vps->{$key} = $value;
        }

        $args = [
            'vps' => $details,
        ];

        return $this->adapter->put('vps/'.$name, $args, true);
    }
}
