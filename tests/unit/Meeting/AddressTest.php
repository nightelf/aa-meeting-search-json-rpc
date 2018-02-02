<?php

namespace Meeting;

use PHPUnit\Framework\TestCase;

/**
 * Class MeetingTest
 */
class AddressTest extends TestCase
{
    /**
     * Test fill from constructor
     */
    public function testFillFromConstructor() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $addressArray =  $responseArray['result'][0]['address'];
        $address = new Address($addressArray);
        $this->doAsserts($address);
    }

    /**
     * Test fill from array
     */
    public function testFillFromArray() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $addressArray = $responseArray['result'][0]['address'];
        $address = new Address();
        $address->fillFromArray($addressArray);
        $this->doAsserts($address);
    }

    /**
     * @param Address $address
     */
    private function doAsserts(Address $address) {

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals(25475, $address->getId());
        $this->assertEquals("940 Hilltop Dr", $address->getStreet());
        $this->assertEquals("Chula Vista", $address->getCity());
        $this->assertEquals("91911", $address->getZip());
        $this->assertEquals("CA", $address->getStateAbbr());
        $this->assertEquals("32.623718461538", $address->getLat());
        $this->assertEquals("-117.05943523077", $address->getLng());
        $this->assertNull($address->getName());
    }
}