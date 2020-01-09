<?php

namespace TransIP\Models;

class FirewallRule
{
    public $description;
    public $startPort;
    public $endPort;
    public $protocol;
    public $whitelist = [];

    public function __construct($description, $startPort, $endPort, $protocol = 'tcp', $whitelist = [])
    {
        $this->description = $description;
        $this->startPort = $startPort;
        $this->endPort = $endPort;
        $this->protocol = $protocol;
        $this->whitelist = $whitelist;
    }
}
