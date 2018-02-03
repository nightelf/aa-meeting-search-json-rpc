<?php

namespace Meeting;

use Geocoder\StatefulGeocoder;
use PHPUnit\Framework\TestCase;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Http\Adapter\Guzzle6\Client as GuzzleClient;

/**
 * Class MeetingTest
 */
class AttendeeMailerTest extends TestCase
{
    /**
     * @var string
     */
    const TEMPLATE_PATH = __DIR__ . '/../../../views/templates/';

    /**
     * @var string
     */
    const TEMPLATE_CACHE_PATH = self::TEMPLATE_PATH . 'cache/';

    /**
     * Test fill from constructor
     */
    public function testRenderTemplate() {


        $referenceAddress = new Address();
        $referenceAddress->setStreet('13934 Recuerdo Drive');
        $referenceAddress->setCity('San Diego');
        $referenceAddress->setStateAbbr('CA');
        $referenceAddress->setZip('92014');
        $referenceAddress->setLat('32.9509082');
        $referenceAddress->setLng('-117.254483');
        $attendee = new Attendee('Yoda', 'yoda@foo.bar', 'Tuesday', $referenceAddress);

        $jsonString = file_get_contents(__dir__ . '/MeetingResponseSample.json');
        $responseArray = json_decode($jsonString, true);
        $meetingCollection = new MeetingCollection($responseArray['result']);
        $meetingCollection->sortByDistance($referenceAddress);

        $attendeeMailer = AttendeeMailer::build(self::TEMPLATE_PATH, [
            'auto_reload' => true,
            'cache' => false,
        ]);
        $html = $attendeeMailer->renderTemplate($attendee, $meetingCollection);

        $this->assertContains('Yoda', $html);
        $this->assertContains("Chula Vista Presbyterian Church", $html);
        $this->assertContains("13934 Recuerdo Dr, Del Mar, CA 92014", $html);
        $this->assertContains('tuesday', $html);
        $this->assertContains('1900', $html);
    }
}