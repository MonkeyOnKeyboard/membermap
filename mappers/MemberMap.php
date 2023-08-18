<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Mappers;

use Ilch\Pagination;
use Modules\Membermap\Models\MemberMap as Model;

class MemberMap extends \Ilch\Mapper
{
    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['g.id' => 'ASC'], Pagination $pagination = null): ?array
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
     * @return array|null
     */
    public function getMmp(): ?array
    {
        return $this->getEntriesBy(['u.id IS NOT' => null, 'lat !=' => '']);
    }  

    /**
     * Gets the Empty Entries.
     *
     * @return array|null
     */
    public function getMmpEmpty(): ?array
    {
        return $this->getEntriesBy(['u.id IS' => null]);
    }  

    /**
     * Gets the Entrie by Userid.
     *
     * @param int $user_id
     * @return Model|null
     */
    public function getMmapByID(int $user_id): ?Model
    {
        $model = $this->getEntriesBy(['u.id' => $user_id]);

        if (!empty($model)) {
            return reset($model);
        }

        return null;
    }

    /**
     * Inserts or updates entry.
     *
     * @param Model $model
     * @return int
     */
    public function save(Model $model): int
    {
        $fields = [
            'user_id' => $model->getUser_Id(),
            'street' => $model->getStreet(),
            'city' => $model->getCity(),
            'zip_code' => $model->getZip_code(),
            'country_code' => $model->getCountry_code(),
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
     * get lat/long for the entry.
     *
     * @param Model $model
     * @return Model
     */
    public function makeLatLng(Model $model): Model
    {
        $street = '';
        if ($model->getStreet() != "") {
            $street			= $model->getStreet();
            $street			= strtolower($street);
            $street			= str_replace(array('ä','ü','ö','ß'), array('ae', 'ue', 'oe', 'ss'), $street );
            $street			= preg_replace("/[^a-z0-9\_\s]/", "", $street);
            $street			= str_replace( array(' ', '--'), array('-', '-'), $street );
        }
        $zip_code           = $model->getZip_code();
        $city               = $model->getCity();
        $country_code       = $model->getCountry_code();

        $url = 'https://nominatim.openstreetmap.org/search.php?' . ($street ? 'street=' . urlencode($street) : '') . '&city=' . urlencode($city).'&country=' . urlencode($country_code) . '&postalcode=' . urlencode($zip_code) . '&format=jsonv2';

        $json = url_get_contents($url, false);
        $output = json_decode($json, true);

        if (isset($output[0])) {
            $model->setLat($output[0]['lat'])
                ->setLng($output[0]['lon']);
        } else {
            $model->setLat('')
                ->setLng('');
        }

        return $model;
    }

    /**
     * Deletes the entry.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)$this->db()->delete('membermap')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes the entry by Userid.
     *
     * @param int $user_id
     * @return bool
     */
    public function deleteUser(int $user_id): bool
    {
        return (bool)$this->db()->delete('membermap')
            ->where(['user_id' => $user_id])
            ->execute();
    }
}
