<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Membermap\Models;

class Gmap extends \Ilch\Model
{
    
    protected $id;
    protected $user_id;
    protected $name;
    protected $city;
    protected $zip_code;
    protected $country_code;
    
    
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    
    
    public function setUser_Id($user_id)
    {
        $this->user_id = $user_id;
        
        return $this;
    }
    
    public function getUser_Id()
    {
        return $this->user_id;
    }
    
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setCity($city)
    {
        $this->city = $city;
        
        return $this;
    }
    
    public function getCity()
    {
        return $this->city;
    }
    
    
    public function setZip_code($zip_code)
    {
        $this->zip_code = $zip_code;
        
        return $this;
    }
    
    public function getZip_code()
    {
        return $this->zip_code;
    }
    
    
    
    public function setCountry_code($country_code)
    {
        $this->country_code = $country_code;
        
        return $this;
    }
    
    public function getCountry_code()
    {
        return $this->country_code;
    }
    
    
}
