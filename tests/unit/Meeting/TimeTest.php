<?php

namespace Meeting;

use PHPUnit\Framework\TestCase;

/**
 * Class MeetingTest
 */
class TimeTest extends TestCase
{
    /**
     * Test fill from constructor
     */
    public function testFillFromConstructor() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $timeArray =  $responseArray['result'][0]['time'];
        $time = new Time($timeArray);
        $this->doAsserts($time);
    }

    /**
     * Test fill from array
     */
    public function testFillFromArray() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $timeArray = $responseArray['result'][0]['time'];
        $time = new Time();
        $time->fillFromArray($timeArray);
        $this->doAsserts($time);
    }

    /**
     * @param Time $time
     */
    private function doAsserts(Time $time) {

        $this->assertInstanceOf(Time::class, $time);
        $this->assertEquals(15455, $time->getId());
        $this->assertEquals(1930, $time->getHour());
        $this->assertEquals("monday", $time->getDay());
    }
}