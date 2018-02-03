<?php

namespace Meeting;

use PHPUnit\Framework\TestCase;

/**
 * Class MeetingTest
 */
class MeetingCollectionTest extends TestCase
{
    /**
     * Test fill from constructor
     */
    public function testFillFromConstructor() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meetingCollection = new MeetingCollection($responseArray['result'], 'San Diego', 'CA');
        $this->doFillAsserts($meetingCollection);
    }

    /**
     * Test fill from array
     */
    public function testFillFromArray() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meetingCollection = new MeetingCollection();
        $meetingCollection->fillFromArray($responseArray['result']);
        $meetingCollection->setCity('San Diego');
        $meetingCollection->setStateAbbrev('CA');
        $this->doFillAsserts($meetingCollection);
    }

    /**
     * Test filter by day
     */
    public function testFilterByDay() {

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meetingCollection = new MeetingCollection($responseArray['result'], 'San Diego', 'CA');
        $filteredMeetingCollection = $meetingCollection->filterByDay('Monday');

        $this->assertEquals(1, count($filteredMeetingCollection));
        $this->assertEquals(60954, $meetingCollection[0]->getId());
    }

    /**
     * Test sort by distance
     */
    public function testSortByDistance() {

        $referenceAddress = new Address();
        $referenceAddress->setLat('32.7107335');
        $referenceAddress->setLng('-117.1629242');
        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meetingCollection = new MeetingCollection($responseArray['result'], 'San Diego', 'CA');
        $meetingCollection->sortByDistance($referenceAddress);

        // 8.51 miles from reference
        $this->assertEquals(60954, $meetingCollection[0]->getId());
        // 12.07 miles from reference
        $this->assertEquals(60955, $meetingCollection[1]->getId());
        // 17.39 miles from refernce
        $this->assertEquals(60956, $meetingCollection[2]->getId());
    }

    /**
     * @param MeetingCollection $meetingCollection
     */
    private function doFillAsserts(MeetingCollection $meetingCollection) {

        $this->assertInstanceOf(MeetingCollection::class, $meetingCollection);
        foreach ($meetingCollection as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
        }
        $this->assertEquals(3, count($meetingCollection));
    }
}