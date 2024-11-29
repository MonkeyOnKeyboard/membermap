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
    public function getEntriesBy(array $where = [], array $orderBy = ['g.id' => 'ASC'], ?Pagination $pagination = null): ?array
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
                ->setUserId($mmpRow['user_id'])
                ->setName($mmpRow['name'] ?? '')
                ->setStreet($mmpRow['street'] ?? '')
                ->setCity($mmpRow['city'])
                ->setZipcode($mmpRow['zip_code'])
                ->setCountryCode($mmpRow['country_code'])
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
            'user_id' => $model->getUserId(),
            'street' => $model->getStreet(),
            'city' => $model->getCity(),
            'zip_code' => $model->getZipCode(),
            'country_code' => $model->getCountryCode(),
            'lat' => $model->getLat(),
            'lng' => $model->getLng()
        ];

        $model = $this->getMmapByID($model->getUserId());

        if ($model) {
            $user_id = $model->getUserId();
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
        $fullAddress = '';
        if ($model->getStreet() != "") {
            $fullAddress         = $model->getStreet();
            $fullAddress         = strtolower($fullAddress);
            $fullAddress         = str_replace(['Str.', 'str.'], ['Straße', 'straße'], $fullAddress);
        }
        $zip_code           = $model->getZipCode();
        $city               = $model->getCity();
        $country_code       = $model->getCountryCode();

        $output = null;
        if (!empty($fullAddress) && !empty($zip_code) && !empty($city) && !empty($country_code)) {
            preg_match('/^(.*?)(\s+\d+.*)$/', $fullAddress, $matches);

            if (count($matches) === 3) {
                $street = $matches[1];
                $housenumber = trim($matches[2]);

                $query = sprintf(
                    '[out:json];area["name"="%s"]->.searchArea;way["addr:street"~"%s",i]["addr:housenumber"="%s"]["addr:postcode"="%s"](area.searchArea);out center;',
                    $city,
                    $street,
                    $housenumber,
                    $zip_code
                );
            } else {
                $street = $fullAddress;

                $query = sprintf(
                    '[out:json];area["name"="%s"]->.searchArea;way["addr:street"~"%s",i]["addr:postcode"="%s"](area.searchArea);out center;',
                    $city,
                    $street,
                    $zip_code
                );
            }

            $url = 'https://overpass-api.de/api/interpreter?data=' . urlencode($query);

            $json = url_get_contents($url);
            $output = json_decode($json, true);
        }

        if (isset($output['elements'][0]['center'])) {
            $model->setLat($output['elements'][0]['center']['lat'])
                ->setLng($output['elements'][0]['center']['lon']);
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
