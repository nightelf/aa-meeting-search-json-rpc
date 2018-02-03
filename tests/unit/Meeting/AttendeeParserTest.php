<?php

namespace Meeting;

use Geocoder\StatefulGeocoder;
use PHPUnit\Framework\TestCase;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Http\Adapter\Guzzle6\Client as GuzzleClient;

/**
 * Class MeetingTest
 */
class AttendeeParserTest extends TestCase
{
    /**
     * Test fill from constructor
     */
    public function testParseCsvFile() {

        $filePath = __dir__ . '/attendee_csv_file.csv';
        $httpClient = new GuzzleClient();
        $provider = new GoogleMaps($httpClient);
        $geocoder = new StatefulGeocoder($provider, 'en');

        $attendeeParser = $this->getMockBuilder(AttendeeParser::class)
            ->setConstructorArgs([
                $geocoder,
                new AttendeeCollection(),
            ])
            ->setMethods(['getGeoCodedAddress'])
            ->getMock();
        $address = new Address([
            'lat' => "32.623718461538",
            'lng' => "-117.05943523077",
        ]);
        $attendeeParser->expects($this->once())
            ->method('getGeoCodedAddress')
            ->willReturn($address);

        $attendeeCollection = $attendeeParser->parseCsvFile($filePath);

        $this->assertEquals(1, $attendeeCollection->count());
        $attendee = $attendeeCollection[0];
        $this->assertEquals('John Fidula', $attendee->getName());
        $this->assertEquals('john.fidula@gmail.com', $attendee->getEmail());
        $this->assertEquals('Monday', $attendee->getPreferredDay());
        $this->assertEquals("32.623718461538", $attendee->getAddress()->getLat());
        $this->assertEquals("-117.05943523077", $attendee->getAddress()->getLng());
    }
}