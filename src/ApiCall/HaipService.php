<?php

namespace TransIP\ApiCall;

class HaipService extends AbstractApiCall
{
    /**
     * @var array $loadBalancingMode the kind of load balancing needed.
     */
    protected $loadBalancingMode = [
        'roundrobin',
        'cookie',
        'source',
    ];

    public function haip($haipName)
    {
        return $this->adapter->get(sprintf('haips/%s', $haipName));
    }

    public function haips()
    {
        return $this->adapter->get('haips');
    }

    public function order($productName, $description = '')
    {

        $args = [
            'productName' => $productName,
            'description' => $description,
        ];

        return $this->adapter->post('haips', $args, true);
    }
}
