<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Models;

class MemberMap extends \Ilch\Model
{
    /**
     * ID.
     *
     * @var int
     */
    protected $id;

    /**
     * User ID.
     *
     * @var int
     */
    protected $user_id;

    /**
     * Username.
     *
     * @var string
     */
    protected $name;

    /**
     * Street.
     *
     * @since Version 1.2.0
     *
     * @var string
     */
    protected $street;

    /**
     * City.
     *
     * @var string
     */
    protected $city;

    /**
     * Zip Code.
     *
     * @var string
     */
    protected $zip_code;

    /**
     * Country Code.
     *
     * @var string
     */
    protected $country_code;
    
    /**
     * lat.
     *
     * @var string
     */
    protected $lat;

     /**
     * lng.
     *
     * @var string
     */
    protected $lng;

    /**
     * Set ID.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): MemberMap
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set User ID.
     *
     * @param int $user_id
     * @return $this
     */
    public function setUser_Id(int $user_id): MemberMap
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get User ID.
     *
     * @return int
     */
    public function getUser_Id(): int
    {
        return $this->user_id;
    }

    /**
     * Set Username.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): MemberMap
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get Username.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Street.
     *
     * @param string $street
     * @return $this
     * @since Version 1.2.0
     *
     */
    public function setStreet(string $street): MemberMap
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Get Street.
     *
     * @since Version 1.2.0
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set City.
     *
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): MemberMap
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get City.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set Zip Code.
     *
     * @param string $zip_code
     * @return $this
     */
    public function setZip_code(string $zip_code): MemberMap
    {
        $this->zip_code = $zip_code;
        return $this;
    }

    /**
     * Get Zip Code.
     *
     * @return string
     */
    public function getZip_code(): string
    {
        return $this->zip_code;
    }

    /**
     * Set Country Code.
     *
     * @param string $country_code
     * @return $this
     */
    public function setCountry_code(string $country_code): MemberMap
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     * Get Country Code.
     *
     * @return string
     */
    public function getCountry_code(): string
    {
        return $this->country_code;
    }
    
    /**
     * Set lat.
     *
     * @param string $lat
     * @return $this
     * @since Version 1.3.0
     */
    public function setLat(string $lat): MemberMap
    {
        $this->lat = $lat;
        return $this;
    }
    
    /**
     * Get lat.
     *
     * @since Version 1.3.0
     * @return string
     */
    public function getLat(): string
    {
        return $this->lat;
    }
    
    /**
     * Set lng.
     *
     * @param string $lng
     * @return $this
     * @since Version 1.3.0
     */
    public function setLng(string $lng): MemberMap
    {
        $this->lng = $lng;
        return $this;
    }
    
    /**
     * Get lng.
     *
     * @since Version 1.3.0
     * @return string
     */
    public function getLng(): string
    {
        return $this->lng;
    }
}
