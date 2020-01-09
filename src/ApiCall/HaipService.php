<?php

namespace TransIP\ApiCall;

class HaipService extends AbstractApiCall
{
    public function haip($haipName)
    {
        return $this->adapter->get(sprintf('haips/%s', $haipName));
    }

    public function haips()
    {
        return $this->adapter->get('haips');
    }
}
