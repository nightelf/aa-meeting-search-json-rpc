<?php

namespace Meeting;

/**
 * Class DateTime
 * @package Meeting
 */
class Time {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $day;

    /**
     * @var $hour
     */
    private $hour;

    /**
     * Address constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null) {

        if ($data) {
            $this->fillFromArray($data);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDay() {

        return $this->day;
    }

    /**
     * @param string $day
     */
    public function setDay($day) {

        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getHour() {

        return $this->hour;
    }

    /**
     * @param mixed $hour
     */
    public function setHour($hour) {

        $this->hour = $hour;
    }

    /**
     * @return string|null
     */
    public function getTimeOfDay() {

        $time = strtotime(str_pad($this->hour, 4, "0", STR_PAD_LEFT));
        if (false !== $time) {
            return date('g:i A', $time);
        }

        return null;
    }

    /**
     * @param array $data
     */
    public function fillFromArray(array $data) {

        $topLevelKeys = [
            'id',
            'day',
            'hour',
        ];

        foreach ($topLevelKeys as $key) {

            if (isset($data[$key]) && $data[$key] && property_exists($this, $key)) {
                $this->$key = $data[$key];
            }
        }
    }
}