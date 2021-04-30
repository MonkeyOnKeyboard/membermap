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
  
  
  public function getMmapByID($user_id)
  {
      $numbersRow = $this->db()->select('*')
      ->from(['membermap'])
      ->where(['user_id' => $user_id])
      ->execute()
      ->fetchAssoc();
      
      if (empty($numbersRow)) {
          return [];
      }
      
      $PhonebookModel = new GmapModel();
      $PhonebookModel ->setId($numbersRow['id'])
      ->setUser_Id($numbersRow['user_id'])
      ->setCity($numbersRow['city'])
      ->setZip_code($numbersRow['zip_code'])
      ->setCountry_code($numbersRow['country_code']);
      
      return $PhonebookModel;
  }
  
  
  
  public function save(GmapModel $membermap)
  {
      $fields = [
          'user_id' => $membermap->getUser_Id(),
          'city' => $membermap->getCity(),
          'zip_code' => $membermap->getZip_code(),
          'country_code' => $membermap->getCountry_code()
      ];
      
      $user_id = (int)$this->db()->select('user_id')
      ->from('membermap')
      ->where(['user_id' => $membermap->getUser_Id()])
      ->execute()
      ->fetchCell();
      
      if ($user_id) {
          $this->db()->update('membermap')
          ->values($fields)
          ->where(['user_id' => $user_id])
          ->execute();
      } else {
          $user_id = $this->db()->insert('membermap')
          ->values($fields)
          ->execute();
      }
      
      return $id;
  }

}