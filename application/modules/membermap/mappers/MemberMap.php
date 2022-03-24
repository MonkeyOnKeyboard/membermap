<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Mappers;

use \Modules\Membermap\Models\MemberMap as Model;

class MemberMap extends \Ilch\Mapper
{

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['g.id' => 'ASC'], $pagination = null)
    {
        $select = $this->db()->select()
        ->fields(['g.id', 'g.user_id', 'g.street', 'g.city', 'g.zip_code', 'g.country_code', 'g.lat', 'g.lng'])
            ->from(['g' => 'membermap'])
            ->join(['u' => 'users'], 'u.id = g.user_id', 'LEFT', ['u.name'])
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $resultArray = $result->fetchRows();

        if (empty($resultArray)) {
            return null;
        }

        $mmp = [];
        foreach ($resultArray as $mmpRow) {
            $model = new Model();
            $model->setId($mmpRow['id'])
                ->setUser_Id($mmpRow['user_id'])
                ->setName($mmpRow['name'] ?? '')
                ->setStreet($mmpRow['street'] ?? '')
                ->setCity($mmpRow['city'])
                ->setZip_code($mmpRow['zip_code'])
                ->setCountry_code($mmpRow['country_code'])
                ->setLat($mmpRow['lat'])
                ->setLng($mmpRow['lng']);
            $mmp[] = $model;
        }
        return $mmp;
    }

    /**
     * Gets the NOT Empty Entries.
     *
     * @param array $where
     * @return array|null
     */
    public function getMmp()
    {
        return $this->getEntriesBy(['u.id IS NOT' => null]);
    }  

    /**
     * Gets the Empty Entries.
     *
     * @return array|null
     */
    public function getMmpEmpty()
    {
        return $this->getEntriesBy(['u.id IS' => null]);
    }  

    /**
     * Gets the Entrie by Userid.
     *
     * @param int $user_id
     * @return Model|null
     */
    public function getMmapByID(int $user_id)
    {
        $model = $this->getEntriesBy(['u.id' => (int)$user_id]);

        if (!empty($model)) {
            return reset($model);
        }

        return null;
    }

    /**
     * Inserts or updates entry.
     *
     * @param Model $model
     * @return boolean|integer
     */
    public function save(Model $model)
    {
        $fields = [
            'user_id' => $model->getUser_Id(),
            'street' => $model->getStreet(),
            'city' => $model->getCity(),
            'zip_code' => $model->getZip_code(),
            'country_code' => $model->getCountry_code()
        ];

        $model = $this->getMmapByID($model->getUser_Id());

        if ($model) {
            $user_id = $model->getUser_Id();
            $this->db()->update('membermap')
                ->values($fields)
                ->where(['user_id' => $user_id])
                ->execute();
        } else {
            $user_id = $this->db()->insert('membermap')
                ->values($fields)
                ->execute();
        }

        return $user_id;
    }

    
    /**
     * Inserts or updates entry.
     *
     * @param Model $model
     * @return boolean|integer
     */
    public function saveLatLng(Model $model)
    {
        $fields = [
            'lat' => $model->getLat(),
            'lng' => $model->getLng()
        ];
        
        $model = $this->getMmapByID($model->getUser_Id());
        
        if ($model) {
            $user_id = $model->getUser_Id();
            $this->db()->update('membermap')
            ->values($fields)
            ->where(['user_id' => $user_id])
            ->execute();
        } else {
            $user_id = $this->db()->insert('membermap')
            ->values($fields)
            ->execute();
        }
        
        return $user_id;
    }
    
    
    /**
     * Deletes the entry.
     *
     * @param int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        return $this->db()->delete('membermap')
            ->where(['id' => (int) $id])
            ->execute();
    }

    /**
     * Deletes the entry by Userid.
     *
     * @param int $user_id
     * @return boolean
     */
    public function deleteUser(int $user_id)
    {
        return $this->db()->delete('membermap')
            ->where(['user_id' => (int) $user_id])
            ->execute();
    }

}