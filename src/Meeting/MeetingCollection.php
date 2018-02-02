<?php

namespace Meeting;

use ArrayObject;
use Location\Coordinate;
use Location\Distance\Vincenty;

/**
 * Class MeetingCollection
 * @package Meeting
 */
class MeetingCollection extends ArrayObject
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

    /**
     * @param string $dayOfWeek
     * @return MeetingCollection
     */
    public function filterByDay($dayOfWeek) {

        $dayOfWeek = strtolower($dayOfWeek);
        $filteredCollection = new static();
        foreach ($this as $meeting) {
            if ($dayOfWeek == $meeting->getTime()->getDay()) {

                $filteredCollection[] = $meeting;
            }
        }
        return $filteredCollection;
    }

    /**
     * @param Address $address
     */
    public function sortByDistance(Address $address) {

        $calculator = new Vincenty();
        $coordinateRef = new Coordinate($address->getLat(), $address->getLng());

        $this->uasort(function ($a, $b) use ($calculator, $coordinateRef) {

            $coordinateA = new Coordinate($a->getAddress()->getLat(), $a->getAddress()->getLng());
            $coordinateB = new Coordinate($b->getAddress()->getLat(), $b->getAddress()->getLng());

            return $calculator->getDistance($coordinateRef, $coordinateA) <=> $calculator->getDistance($coordinateRef, $coordinateB);
        });

        $this->exchangeArray(array_values($this->getArrayCopy()));
    }
}