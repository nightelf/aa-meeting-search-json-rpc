<?php

namespace Meeting;

use ArrayObject;
use Location\Coordinate;
use Location\Distance\Vincenty;

/**
 * Class MeetingCollection
 * @package Meeting
 */
class AttendeeCollection extends ArrayObject
{
    /**
     * Meeting constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null) {

        if ($data) {
            $this->fillFromArray($data);
        }
    }

    /**
     * @param array $data
     * @return Meeting
     */
    public function fillFromArray(array $data) {

        foreach ($data as $datum) {
            $this[] = new Meeting($datum);
        }
    }
}