<?php

namespace Meeting;

use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use file;

class AttendeeParser {

    /**
     * @var StatefulGeocoder
     */
    private $geocoder;

    /**
     * @var StatefulGeocoder
     */
    private $attendeeCollection;

    /**
     * AttendeeParser constructor.
     * @param StatefulGeocoder $geocoder
     * @param AttendeeCollection $attendeeCollection
     */
    public function __construct(StatefulGeocoder $geocoder, AttendeeCollection $attendeeCollection) {

        $this->geocoder = $geocoder;
        $this->attendeeCollection = $attendeeCollection;
    }

    /**
     * @param string $path
     * @return StatefulGeocoder|AttendeeCollection
     */
    public function parseCsvFile($path) {

        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 2048, ",")) !== FALSE) {

                if (count($data) == 4) {
                    list($name, $email, $preferredDay, $addressString) = $data;
                    $attendee = new Attendee(trim($name), trim($email), trim($preferredDay));
                    if ($addressString) {
                        if ($address = $this->getGeoCodedAddress(trim($addressString))) {
                            $attendee->setAddress($address);
                        }
                    }
                    $this->attendeeCollection[] = $attendee;
                }
            }
            fclose($handle);
        }
        return $this->attendeeCollection;
    }

    /**
     * Get geocode
     * @param string $addressText
     * @return Address|null
     */
    public function getGeoCodedAddress($addressText) {

        $referenceAddress = null;
        $googleAddressCollection = $this->geocoder->geocodeQuery(GeocodeQuery::create($addressText));

        if ($googleAddressCollection->count() == 1) {
            $googleAddress = $googleAddressCollection->first();

            $streetAddress = $googleAddress->getStreetNumber() . ' ' .  $googleAddress->getStreetName();
            $zip = $googleAddress->getPostalCode();
            $city = $googleAddress->getLocality();
            $stateAbbrev = null;
            foreach ($googleAddress->getAdminLevels() as $adminLevel) {

                if ($adminLevel->getLevel() == 1) { // yes, 1 is a magic number. Need to find constant.

                    $stateAbbrev = $adminLevel->getCode();
                    break;
                }
            }

            $referenceAddress = new Address();
            $referenceAddress->setStreet($streetAddress);
            $referenceAddress->setCity($city);
            $referenceAddress->setStateAbbr($stateAbbrev);
            $referenceAddress->setZip($zip);
            if ($coordinates = $googleAddress->getCoordinates()) {
                $referenceAddress->setLat($coordinates->getLatitude());
                $referenceAddress->setLng($coordinates->getLongitude());
            }
        }

        return $referenceAddress;
    }

    /**
     * @return AttendeeParser
     */
    public static function build() {

        $httpClient = new GuzzleClient();
        $provider = new GoogleMaps($httpClient);
        $geocoder = new StatefulGeocoder($provider, 'en');
        return new self($geocoder, new AttendeeCollection());
    }
}