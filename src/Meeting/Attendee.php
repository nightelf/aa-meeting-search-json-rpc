<?php

namespace Meeting;

/**
 * Class Attendee
 * @package Meeting
 */
class Attendee {

    /**
     * @var null
     */
    private $name;

    /**
     * @var null
     */
    private $email;

    /**
     * @var string
     */
    private $preferredDay;

    /**
     * @var Address
     */
    private $address;

    /**
     * Attendee constructor.
     * @param string|null $name
     * @param string|null $email
     * @param string|null $preferredDay
     * @param Address|null $address
     */
    public function __construct($name = null, $email = null, $preferredDay = null, Address $address = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->preferredDay = $preferredDay;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPreferredDay(): string
    {
        return $this->preferredDay;
    }

    /**
     * @param string $preferredDay
     */
    public function setPreferredDay(string $preferredDay)
    {
        $this->preferredDay = $preferredDay;
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
}