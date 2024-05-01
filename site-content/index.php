<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
//error_reporting(E_ALL);
require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

if(isset($_GET['ip'])) {
    $ipAddr = str_replace("/", "", $_GET['ip']);
    if($_GET['ip'] == "") {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
} else {
    $ipAddr = $_SERVER['REMOTE_ADDR'];
}

try {
    $asn_database = new Reader('/usr/share/GeoIP/GeoLite2-ASN.mmdb');
    $asn_reader = $asn_database->asn($ipAddr);

    $city_database = new Reader('/usr/share/GeoIP/GeoLite2-City.mmdb');
    $city_reader = $city_database->city($ipAddr);

    $country_database = new Reader('/usr/share/GeoIP/GeoLite2-Country.mmdb');
    $country_reader = $country_database->country($ipAddr);

    $jsonResponse = array(
        'asn' => [
            'asn' => $asn_reader->autonomousSystemNumber,
            'organization' => $asn_reader->autonomousSystemOrganization,
            'network' => $asn_reader->network,
            'ip' => $asn_reader->ipAddress,
        ],
        'city' => [
            'city' => $city_reader->city->names['en'],
            'postal' => (int)$city_reader->postal->code,
            'continent' => [
                'code' => $city_reader->continent->code,
                'names' => $city_reader->continent->names['en'],
            ],
            'country' => [
                'iso_code' => $city_reader->country->isoCode,
                'name' => $city_reader->country->names['en'],
            ],
            'location' => [
                'latitude' => $city_reader->location->latitude,
                'longitude' => $city_reader->location->longitude,
                'time_zone' => $city_reader->location->timeZone,
            ],
        ],
        'country' => [
            'name' => $country_reader->country->names['en'],
            'continent' => $country_reader->continent->names['en'],
        ],
    );

    header('Content-Type: application/json');
    echo json_encode($jsonResponse);
}
catch (InvalidArgumentException) {
    $jsonResponse = array(
        'ip' => $ipAddr,
        'error' => "Invalid Argument",
    );
    header('Content-Type: application/json');
    echo json_encode($jsonResponse);
}
catch (GeoIp2\Exception\AddressNotFoundException) {
    $jsonResponse = array(
        'ip' => $ipAddr,
        'error' => "Address Not Found",
    );
    header('Content-Type: application/json');
    echo json_encode($jsonResponse);
}
catch (Exception $e) {
    $jsonResponse = array(
        'ip' => $ipAddr,
        'error' => $e->getMessage(),
    );
    header('Content-Type: application/json');
    echo json_encode($jsonResponse);
}