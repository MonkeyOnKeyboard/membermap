<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Membermap\Mappers;

use \Modules\Membermap\Models\Gmap as GmapModel;
use \Modules\User\Models\User as UserModel;

class Gmap extends \Ilch\Mapper
{
    
    public function getMmp($where = []) {

      $resultArray = $this->db()->select()
      ->fields(['g.id', 'user_id' => 'g.user_id', 'u.name', 'g.city', 'g.zip_code', 'g.country_code'])
      ->from(['g' => 'membermap'])
      ->join(['u' => 'users'], 'u.id = g.user_id')
      ->where($where)
      ->order(['g.id' => 'ASC'])
      ->execute()
      ->fetchRows();
      
          
      
      if (empty($resultArray)) {
          return null;
      }
      
      $mmp = [];
      foreach ($resultArray as $mmpRow) {
          $model = new GmapModel();
          $model->setId($mmpRow['id'])
          ->setUser_Id($mmpRow['user_id'])
          ->setName($mmpRow['name'])
          ->setCity($mmpRow['city'])
          ->setZip_code($mmpRow['zip_code'])
          ->setCountry_code($mmpRow['country_code']);
          $mmp[] = $model;
      }
      return $mmp;
  }  
    
  public function getUserLocations($where = []) {
    
      $resultArray = $this->db()->select()
      ->fields(['id', 'name', 'city'])
      ->from(['users'])
      ->where($where)
      ->order(['id' => 'ASC'])
      ->execute()
      ->fetchRows();
      
      if (empty($resultArray)) {
          return null;
      }
      
      $mlocations = [];
      foreach ($resultArray as $memberRow) {
          $model = new UserModel();
          $model->setId($memberRow['id'])
          ->setName($memberRow['name'])
          ->setCity($memberRow['city']);
          $mlocations[] = $model;
      }
      
      return $mlocations;
      
      
  }  
    
}
