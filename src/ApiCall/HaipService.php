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

    /**
     * @var array $ipSetup which kind of IP protocols to use.
     */
    protected $ipSetup = [
        'both',
        'noipv6',
        'ipv6to4',
    ];
    /**
     * @var array $endTimes When to delete? Immediately or at the end of current Period?
     */
    protected $endTimes = [
        'end',
        'immediately',
    ];

    public function addExisitingCertificate($haipName, $sslCertificateId)
    {
        $args = [
            'sslCertificateId' => $sslCertificateId,
        ];

        return $this->adapter->post(sprintf('haips/%s/certificates', $haipName), $args, true);
    }

    public function addLetsEncryptCertificate($haipName, $commonName)
    {
        $args = [
            'commonName' => $commonName,
        ];

        return $this->adapter->post(sprintf('haips/%s/certificates', $haipName), $args, true);
    }

    public function cancel($haipName, $endTime = 'end')
    {
        if (!in_array($endTime, $this->endTimes)) {
            $this->adapter->reportError(406, 'Not an available endTime given.');
        }

        $args = [
            'endTime' => $endTime,
        ];

        return $this->adapter->delete(sprintf('haips/%s', $haipName), true);
    }

    public function certificates($haipName)
    {
        return $this->adapter->get(sprintf('haips/%s/certificates', $haipName));
    }

    public function detachCertificate($haipName, $certificateId)
    {
        return $this->adapter->delete(sprintf('haips/%s/certificates/%s', $haipName, $certificateId), true);
    }

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

    public function statusReports($haipName)
    {
        return $this->adapter->get(sprintf('haips/%s/status-reports', $haipName));
    }

    public function update($haipName, $changes = [])
    {
        $details = $this->haip($haipName);
        foreach ($changes as $key => $value) {
            $details->haip->{$key} = $value;
        }

        return $this->adapter->put(sprintf('haips/%s', $haipName), $details, true);
    }
}
