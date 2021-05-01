<?php
/**
* @copyright Ilch 2.0
* @package ilch
*/

namespace Modules\Membermap\Mappers;

use \Modules\Membermap\Models\Gmap as GmapModel;

class Gmap extends \Ilch\Mapper
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
            ->fields(['g.id', 'g.user_id', 'g.city', 'g.zip_code', 'g.country_code'])
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
            $model = new GmapModel();
            $model->setId($mmpRow['id'])
                ->setUser_Id($mmpRow['user_id'])
                ->setName($mmpRow['name'] ?? '')
                ->setCity($mmpRow['city'])
                ->setZip_code($mmpRow['zip_code'])
                ->setCountry_code($mmpRow['country_code']);
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
     * @return GmapModel|null
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
     * @param GmapModel $membermap
     * @return boolean|integer
     */
    public function save(GmapModel $membermap)
    {
        $fields = [
            'user_id' => $membermap->getUser_Id(),
            'city' => $membermap->getCity(),
            'zip_code' => $membermap->getZip_code(),
            'country_code' => $membermap->getCountry_code()
        ];

        $model = $this->getMmapByID($membermap->getUser_Id());

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
        $this->db()->delete('membermap')
            ->where(['user_id' => (int) $user_id])
            ->execute();
    }

}