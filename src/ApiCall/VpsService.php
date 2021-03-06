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
     * @var array $endTimes When to delete? Immediately or at the end of current Period?
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

    public function addIpAddresses($name, $ipAddress)
    {
        $args = [
            'ipAddress' => $ipAddress,
        ];

        return $this->adapter->post(sprintf('vps/%s/ip-addresses', $name), $args, true);
    }

    public function addMailServiceToDomains(array $domainNames)
    {
        $args = [
            'domainNames' => $domainNames,
        ];

        return $this->adapter->post('mail-service', $args, true);
    }

    public function addons($name)
    {
        return $this->adapter->get(sprintf('/vps/%s/addons', $name));
    }

    public function attachVpsToPrivateNetwork($name, $privateNetworkName)
    {
        $args = [
            'action' => 'attachvps',
            'privateNetwork' => $name,
        ];

        return $this->adapter->patch(sprintf('private-networks/%s', $privateNetworkName), $args, true);
    }

    public function availabilityZones()
    {
        return $this->adapter->get('availability-zone');
    }

    public function availabilityZone($zone)
    {
        return $this->adapter->get('availability-zone/' . $zone);
    }

    public function backups($name)
    {
        return $this->adapter->get(sprintf('vps/%s/backups', $name));
    }

    public function bigStorage($bigStorageName)
    {
        return $this->adapter->get(sprintf('big-storages/%s', $bigStorageName));
    }

    public function bigStorageBackups($bigStorageName)
    {
        return $this->adapter->get(sprintf('big-storages/%s/backups', $bigStorageName));
    }

    public function bigStorageUsage($bigStorageName, $dateTimeStart = null, $dateTimeEnd = null, $dateTimeFormat = 'Y-m-d H:i')
    {
        $args = [
            'dateTimeFormat' => $dateTimeFormat,
        ];

        if ($dateTimeStart) {
            $args['dateTimeStart'] = $dateTimeStart;
        }

        if ($dateTimeEnd) {
            $args['dateTimeEnd'] = $dateTimeEnd;
        }

        return $this->adapter->get(sprintf('big-storages/%s/usage', $bigStorageName), $args);
    }

    public function bigStorages($name = null)
    {
        $path = 'big-storages';
        if ($name) {
            $path .= '?vpsName=' . $name;
        }

        return $this->adapter->get($path);
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

    public function cancelAddon($name, $addon)
    {
        $args = [
        ];

        return $this->adapter->delete(sprintf('vps/%s/addons/%s', $name, $addon), $args, true);
    }

    public function cancelBigStorage($bigStorageName, $endTime = 'end')
    {
        if (!in_array($endTime, $this->endTimes)) {
            $this->adapter->reportError(406, 'Not an available endTime given.');
        }

        $args = [
            'endTime' => $endTime,
        ];

        return $this->adapter->delete(sprintf('big-storages/%s', $bigStorageName), $args, true);
    }

    public function cancelPrivateNetwork($privateNetworkName, $endTime = 'end')
    {
        if (!in_array($endTime, $this->endTimes)) {
            $this->adapter->reportError(406, 'Not an available endTime given.');
        }

        $args = [
            'endTime' => $endTime,
        ];

        return $this->adapter->delete(sprintf('private-networks/%s', $privateNetworkName), $args, true);
    }

    public function changeDescription($name, $description)
    {
        $args = $this->vps($name);
        $args->vps->description = $description;

        return $this->adapter->put('vps/' . $name, $args, true);
    }

    public function cloneVps($name, $availabilityZone = 'rtm0')
    {
        $args = [
            'vpsName' => $name,
            'availabilityZone' => $availabilityZone,
        ];

        return $this->adapter->post('vps', $args, true);
    }

    public function convertBackupToSnapshot($name, $backupId, $description)
    {
        $args = [
            'action' => 'convert',
            'description' => $description,
        ];

        return $this->adapter->patch(sprintf('vps/%s/backups/%s', $name, $backupId), $args, true);
    }

    public function createSnapshot($name, $description, $shouldStartVps = true)
    {
        $args = [
            'description' => $description,
            'shouldStartVps' => $shouldStartVps,
        ];

        return $this->adapter->post(sprintf('vps/%s/snapshots', $name), $args, true);
    }

    public function detachVpsFromPrivateNetwork($name, $privateNetworkName)
    {
        $args = [
            'action' => 'detachvps',
            'privateNetwork' => $name,
        ];

        return $this->adapter->patch(sprintf('private-networks/%s', $privateNetworkName), $args, true);
    }

    public function firewall($name)
    {
        return $this->adapter->get(sprintf('vps/%s/firewall', $name));
    }

    public function handover($name, $targetCustomerName)
    {
        $args = [
            'action' => 'handover',
            'targetCustomerName' => $targetCustomerName,
        ];

        return $this->adapter->patch('vps/' . $name, $args, true);
    }

    public function installOperatingSystem($name, $operatingSystemName, $hostname = '', $base64InstallText = '')
    {
        $args = [
            'operatingSystemName' => $operatingSystemName,
            'hostname' => $hostname,
            'base64InstallText' => $base64InstallText,
        ];

        return $this->adapter->post(sprintf('vps/%s/operating-systems', $name), $args, true);
    }

    public function ipAddress($name, $ipAddress)
    {
        return $this->adapter->get(sprintf('vps/%s/ip-addresses/%s', $name, $ipAddress));
    }

    public function ipAddresses($name)
    {
        return $this->adapter->get(sprintf('vps/%s/ip-addresses', $name));
    }

    public function mailService()
    {
        return $this->adapter->get('mail-service');
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

    public function operatingSystems($name)
    {
        return $this->adapter->get(sprintf('vps/%s/operating-systems', $name));
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

    public function orderAddons($name, array $addons)
    {
        $args = [
            'addons' => $addons,
        ];

        return $this->adapter->post(sprintf('vps/%s/addons', $name), $args, true);
    }

    public function orderBigStorage($size = 2, $offsiteBackups = true, $availabilityZone = 'ams0', $vpsName = '')
    {
        $args = [
            'size' => $size,
            'offsiteBackups' => $offsiteBackups,
            'availabilityZone' => $availabilityZone,
            'vpsName' => $vpsName,
        ];

        return $this->adapter->post(sprintf('big-storages'), $args, true);
    }

    public function privateNetwork($privateNetworkName)
    {
        return $this->adapter->get(sprintf('private-networks/%s', $privateNetworkName));
    }

    public function privateNetworks($name = null)
    {
        $path = 'private-networks';
        if ($name) {
            $path .= '?vpsName=' . $name;
        }

        return $this->adapter->get($path);
    }

    public function products()
    {
        return $this->adapter->get('products');
    }

    public function regenerateMailServicePassword()
    {
        return $this->adapter->patch('mail-service', []);
    }

    public function removeIpAddress($name, $ipAddress)
    {
        $args = [
        ];

        return $this->adapter->delete(sprintf('vps/%s/ip-addresses/%s', $name, $addon), $args, true);
    }

    public function removeSnapshot($name, $snapshotName)
    {
        $args = [
        ];

        return $this->adapter->delete(sprintf('vps/%s/snapshots/%s', $name, $snapshotName), $args, true);
    }

    public function reverseDns($name, $ipAddress, $reverseDns)
    {
        $details = $this->ipAddress($name, $ipAddress);
        $details->ipAddress->reverseDns = $reverseDns;

        $args = [
            'ipAddress' => $details,
        ];

        return $this->adapter->put(sprintf('vps/%s/ip-addresses/%s', $name, $ipAddress), $args, true);
    }

    public function revertBackup($name, $backupId)
    {
        $args = [
            'action' => 'revert',
        ];

        return $this->adapter->patch(sprintf('vps/%s/backups/%s', $name, $backupId), $args, true);
    }

    public function revertBigStorage($bigStorageName, $backupId)
    {
        $args = [
            'action' => 'revert',
        ];

        return $this->adapter->patch(sprintf('big-storages/%s/backups/%s', $bigStorageName, $backupId), $args, true);
    }

    public function revertSnapshot($name, $snapshotName, $destinationVpsName = '')
    {
        $args = [
            'destinationVpsName' => $destinationVpsName,
        ];

        return $this->adapter->patch(sprintf('vps/%s/snapshots/%s', $name, $snapshotName), $args, true);
    }

    public function snapshot($name, $snapshot)
    {
        return $this->adapter->get(sprintf('vps/%s/snapshots/%s', $name));
    }

    public function snapshots($name)
    {
        return $this->adapter->get(sprintf('vps/%s/snapshots', $name));
    }

    public function traffic($name)
    {
        return $this->adapter->get('traffic/' . $name);
    }

    public function trafficTotal()
    {
        return $this->adapter->get('traffic');
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

    public function updateBigStorage($bigStorageName, $description)
    {
        $details = $this->bigStorage($bigStorageName);
        $details->bigStorage->description = $description;

        $args = [
            'bigStorage' => $details,
        ];

        return $this->adapter->put(sprintf('big-storages/%s', $bigStorageName), $args, true);
    }

    public function updateFirewall($name, $firewall, bool $active, array $ruleSet = [])
    {
        $args = [
            'VpsFirewall' => [
                'isEnabled' => $active,
                'ruleSet' => $ruleSet,
            ],
        ];

        return $this->adapter->put(sprintf('vps/%s/firewall', $name), $args, true);
    }

    public function updatePrivateNetwork($privateNetworkName, $description)
    {
        $details = $this->privateNetwork($privateNetworkName);
        $details->privateNetwork->description = $description;

        $args = [
            'privateNetwork' => $details,
        ];

        return $this->adapter->put(sprintf('private-networks/%s', $privateNetworkName), $args, true);
    }

    public function upgrade($name, $productName)
    {
        $args = [
            'productName' => $productName,
        ];

        return $this->adapter->post(sprintf('vps/%s/upgrades', $name), $args, true);
    }

    public function upgradeBigStorage($bigStorageName, $size, $offsiteBackups = null)
    {
        $args = [
            'bigStorageName' => $bigStorageName,
            'size' => $size,
        ];

        if ($offsiteBackups) {
            $args['offsiteBackups'] = $offsiteBackups;
        }

        return $this->adapter->post(sprintf('big-storages'), $args, true);
    }

    public function upgrades($name)
    {
        return $this->adapter->get(sprintf('vps/%s/upgrades', $name));
    }

    public function usageData($name)
    {
        return $this->adapter->get(sprintf('vps/%s/usage', $name));
    }

    public function vncData($name)
    {
        return $this->adapter->get(sprintf('vps/%s/vnc-data', $name));
    }

    public function vps($name)
    {
        return $this->adapter->get('vps/' . $name);
    }

    public function vpsList()
    {
        return $this->adapter->get('vps');
    }
}
