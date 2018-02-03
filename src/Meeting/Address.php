<?php

namespace Meeting;

/**
 * Class Address
 * @package Meeting
 */
class Address
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state_abbr;

    /**
     * @var string
     */
    private $lat;

    /**
     * @var string;
     */
    private $lng;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStateAbbr()
    {
        return $this->state_abbr;
    }

    /**
     * @param mixed $state_abbr
     */
    public function setStateAbbr($state_abbr)
    {
        $this->state_abbr = $state_abbr;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function renderAddress() {

        $address = '';
        if ($this->street) {
            $address .= $this->street;
        }
        if ($this->city) {
            $address .= ($address ? ', ' : '')  . $this->city ;
        }
        if ($this->state_abbr) {
            $address .= ($address ? ', ' : '')  . $this->state_abbr ;
        }
        if ($this->zip) {
            $address .= ($address ? ' ' : '')  . $this->zip ;
        }
        return $address;
    }

    /**
     * @param array $data
     * @return Address
     */
    public function fillFromArray(array $data) {

        $topLevelKeys = [
            'id',
            'street',
            'city',
            'zip',
            'state_abbr',
            'lat',
            'lng',
        ];

        foreach ($topLevelKeys as $key) {

            if (isset($data[$key]) && $data[$key] && property_exists($this, $key)) {
                $this->$key = $data[$key];
            }
        }
    }
}