<?php

namespace TransIP\Tests;

class JsonData
{
    private $response = [
        'availability-zone' => '{
          "availabilityZones": [
            {
              "name": "ams0",
              "country": "nl",
              "isDefault": false
            },
            {
              "name": "rtm0",
              "country": "nl",
              "isDefault": true
            }
          ]
        }',

        'availability-zone/ams0' => '{
          "availabilityZone": {
            "name": "ams0",
            "country": "nl",
            "isDefault": true
          }
        }',

        'traffic' => '{
          "trafficInformation": {
            "startDate": "2017-06-22",
            "endDate": "2017-07-22",
            "usedInBytes": 7860253754,
            "usedTotalBytes": 11935325369,
            "maxInBytes": 1073741824000
          }
        }',

        'traffic/example-vps' => '{
          "trafficInformation": {
            "startDate": "2017-06-22",
            "endDate": "2017-07-22",
            "usedInBytes": 7860253754,
            "usedTotalBytes": 11935325369,
            "maxInBytes": 1073741824000
          }
        }',

        'products' => '{
          "vps": [
            {
              "name": "vpsAddon-1-extra-ip-address",
              "description": "1 extra IP address",
              "price": 4.99,
              "renewalPrice": 7.99
            }
          ],
          "vpsAddon": [
            {
              "name": "vpsAddon-1-extra-ip-address",
              "description": "1 extra IP address",
              "price": 4.99,
              "renewalPrice": 7.99
            }
          ],
          "haip": [
            {
              "name": "vpsAddon-1-extra-ip-address",
              "description": "1 extra IP address",
              "price": 4.99,
              "renewalPrice": 7.99
            }
          ],
          "bigStorage": [
            {
              "name": "vpsAddon-1-extra-ip-address",
              "description": "1 extra IP address",
              "price": 4.99,
              "renewalPrice": 7.99
            }
          ],
          "privateNetworks": [
            {
              "name": "vpsAddon-1-extra-ip-address",
              "description": "1 extra IP address",
              "price": 4.99,
              "renewalPrice": 7.99
            }
          ]
        }',

        'vps' => '{
          "vpses": [
            {
              "name": "example-vps",
              "description": "example VPS",
              "productType": "BladeVPS PureSSD X4",
              "productPrice": 20,
              "operatingSystem": "ubuntu-16.04",
              "diskSize": 157286400,
              "memorySize": 4194304,
              "cpus": 2,
              "status": "running",
              "ipAddress": "37.97.254.6",
              "macAddress": "52:54:00:3b:52:65",
              "currentSnapshots": 1,
              "maxSnapshots": 10,
              "isLocked": false,
              "isBlocked": false,
              "isCustomerLocked": false
            }
          ]
        }',

        'vps/example-vps' => '{
          "vps": {
            "name": "example-vps",
            "description": "example VPS",
            "productType": "BladeVPS PureSSD X4",
            "productPrice": 20,
            "operatingSystem": "ubuntu-16.04",
            "diskSize": 157286400,
            "memorySize": 4194304,
            "cpus": 2,
            "status": "running",
            "ipAddress": "37.97.254.6",
            "macAddress": "52:54:00:3b:52:65",
            "currentSnapshots": 1,
            "maxSnapshots": 10,
            "isLocked": false,
            "isBlocked": false,
            "isCustomerLocked": false
          }
        }',

        'vps/vps-not-found' => 404,

        'vps/delete-vps' => 204,

        'traffic/vps-not-found' => 404,
    ];

    /**
     * Will mimic the behavior of calls of the real TransIP API.
     *
     * @param string $url
     * @param array $args
     * @param string $type
     *
     * @return string
     */
    public function getResponse($url, array $args, $type)
    {
        $response = '{}';

        if ($type == 'post') {
            switch ($url) {
                case 'vps':
                    if (isset($args['vpsName'])) {
                        // Clone action
                        if (empty($args['vpsName']) || $args['vpsName'] == 'vps-not-found') {
                            header("HTTP/1.0 404 Not Found");
                            $response =  'Server not Found';
                            break;
                        } else {
                            header("HTTP/1.0 201 Created");
                            $response =  '';
                            break;
                        }
                    } elseif (isset($args['hostname']) && $args['hostname'] == 'ex@mple-vps') {
                        header("HTTP/1.0 406 Not Acceptable");
                        $response = "This is not a valid hostname: 'ex@mple-vps'";
                        break;
                    } else {
                        header("HTTP/1.0 201 Created");
                        $response = '';
                        break;
                    }
                    break;
            }

            return $response;
        }

        if ($response === '{}' && isset($this->response[$url])) {
            if ($this->response[$url] == 404) {
                header("HTTP/1.0 404 Not Found");
                $response = 'Not Found';
                return $response;
            }
            if ($this->response[$url] == 204) {
                header("HTTP/1.0 204 No content");
                $response = 'No content';
                return $response;
            }
            $response = $this->response[$url];
        }

        return $response;
    }
}
