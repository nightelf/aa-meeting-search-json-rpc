<?php

namespace Meeting;

use PHPUnit\Framework\TestCase;

/**
 * Class MeetingTest
 */
class MeetingTest extends TestCase
{
    /**
     * Test fill from constructor
     */
    public function testFillFromConstructor() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meeting = new Meeting( $responseArray['result'][0]);
        $this->doAsserts($meeting);
    }

    /**
     * Test fill from array
     */
    public function testFillFromArray() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meeting = new Meeting();
        $meeting->fillFromArray($responseArray['result'][0]);
        $this->doAsserts($meeting);
    }

    /**
     * @param Meeting $meeting
     */
    private function doAsserts(Meeting $meeting) {
        $this->assertInstanceOf(Meeting::class, $meeting);
        $this->assertEquals(60954, $meeting->getId());
        $this->assertEquals("MeetingItem", $meeting->getType());
        $this->assertEquals("Format: Contact: Adriana - 619-397-7010", $meeting->getDetails());
        $this->assertEquals("OA", $meeting->getMeetingType());
        $this->assertEquals("Chula Vista Presbyterian Church", $meeting->getMeetingName());
        $this->assertEquals("English", $meeting->getLanguage());
        $this->assertInstanceOf(Address::class, $meeting->getAddress());
        $this->assertEquals("Chula Vista Presbyterian Church", $meeting->getAddress()->getName());
    }
}