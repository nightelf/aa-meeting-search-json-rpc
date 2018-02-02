<?php

namespace Meeting;

/**
 * Class Meeting
 * @package Meeting
 */
class Meeting
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var $details
     */
    private $details;

    /**
     * @var $meeting_type
     */
    private $meeting_type;

    /**
     * @var $meeting_name
     */
    private $meeting_name;

    /**
     * @var $language
     */
    private $language;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var Time
     */
    private $time;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getMeetingType()
    {
        return $this->meeting_type;
    }

    /**
     * @param mixed $meeting_type
     */
    public function setMeetingType($meeting_type)
    {
        $this->meeting_type = $meeting_type;
    }

    /**
     * @return mixed
     */
    public function getMeetingName()
    {
        return $this->meeting_name;
    }

    /**
     * @param mixed $meeting_name
     */
    public function setMeetingName($meeting_name)
    {
        $this->meeting_name = $meeting_name;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }


    /**
     * @return Time
     */
    public function getTime(): Time
    {
        return $this->time;
    }

    /**
     * @param Time $time
     */
    public function setTime(Time $time)
    {
        $this->time = $time;
    }

    /**
     * @param array $data
     * @return Meeting
     */
    public function fillFromArray(array $data) {

        $topLevelKeys = [
            'id',
            'type',
            'details',
            'meeting_type',
            'meeting_name',
            'language',
        ];

        foreach ($topLevelKeys as $key) {
            if (isset($data[$key]) && $data[$key] && property_exists($this, $key)) {
                $this->$key = $data[$key];
            }
        }

        $address = new Address();
        if (isset($data['address'])) {

            $address = new Address($data['address']);
            if (isset($data['location'])) {
                $address->setName($data['location']);
            }
        }
        $this->setAddress($address);

        $time = new Time();
        if (isset($data['time'])) {

            $time = new Time($data['time']);
        }
        $this->setTime($time);
    }
}