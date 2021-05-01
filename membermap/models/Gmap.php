<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Membermap\Models;

class Gmap extends \Ilch\Model
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
     * Set ID.
     *
     * @param int
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = (int) $id;
        return $this;
    }
    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set User ID.
     *
     * @param int
     * @return $this
     */
    public function setUser_Id(int $user_id)
    {
        $this->user_id = (int) $user_id;
        
        return $this;
    }
    /**
     * Get User ID.
     *
     * @return int
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }

    /**
     * Set Username.
     *
     * @param string
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        
        return $this;
    }
    /**
     * Get Username.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set City.
     *
     * @param string
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        
        return $this;
    }
    /**
     * Get City.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set Zip Code.
     *
     * @param string
     * @return $this
     */
    public function setZip_code(string $zip_code)
    {
        $this->zip_code = $zip_code;
        
        return $this;
    }
    /**
     * Get Zip Code.
     *
     * @return string
     */
    public function getZip_code()
    {
        return $this->zip_code;
    }

    /**
     * Set Country Code.
     *
     * @param string
     * @return $this
     */
    public function setCountry_code(string $country_code)
    {
        $this->country_code = $country_code;
        
        return $this;
    }
    /**
     * Get Country Code.
     *
     * @return string
     */
    public function getCountry_code()
    {
        return $this->country_code;
    }
    
}
