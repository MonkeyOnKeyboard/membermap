<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Config;

use Ilch\Config\Database;
use Modules\Membermap\Mappers\MemberMap as MemberMapMapper;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'membermap',
        'version' => '1.3.1',
        'icon_small' => 'fa-solid fa-map-location-dot',
        'author' => 'MonkeyOnKeyboard',
        'languages' => [
            'de_DE' => [
                'name' => 'Membermap',
                'description' => 'Eine Mitgliederkarte basierend auf dem vom Benutzer freiwillig eingetragenen Standort. Das Modul unterstützt Google Maps, MapQuest und Open Street Map.',
            ],
            'en_EN' => [
                'name' => 'Membermap',
                'description' => 'A membermap based on the voluntarily entered location of the users. This module supports Google Maps, MapQuest and Open Street Map.',
            ],
        ],

        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new Database($this->db());
        $databaseConfig->set('map_service', 0)
            ->set('map_apikey', '');
    }

    public function uninstall()
    {
        $this->db()->drop('membermap', true);
        $databaseConfig = new Database($this->db());
        $databaseConfig->delete('map_apikey');
        $databaseConfig->delete('map_service');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_membermap` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) UNSIGNED NOT NULL,
            `zip_code` VARCHAR(15) NULL DEFAULT NULL,
            `street` VARCHAR(255) NULL DEFAULT NULL,
            `city` VARCHAR(100) NULL DEFAULT NULL,
            `country_code` VARCHAR(4) NULL DEFAULT NULL,
            `lat` VARCHAR(255) NULL DEFAULT NULL,
            `lng` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `FK_[prefix]_membermap_[prefix]_users` (`user_id`) USING BTREE,
            CONSTRAINT `FK_[prefix]_membermap_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0.0":
                // Map-Service auswahl (1 = MapQuest, 2 = Google, 3 = OSM)
                $databaseConfig = new Database($this->db());
                $databaseConfig->set('map_service', '1');
                // no break
            case "1.1.0":
                // Add column street for optional street entry
                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD COLUMN `street` VARCHAR(255) NULL DEFAULT NULL AFTER `zip_code`;');
                // no break
            case "1.2.0":
                // Add column lat and lng for storing locations latLng
                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD COLUMN `lat` VARCHAR(255) NULL DEFAULT NULL AFTER `country_code`, ADD COLUMN `lng` VARCHAR(255) NULL DEFAULT NULL AFTER `lat`;');
                // update all user
                $mapper = new MemberMapMapper();
                $mmp = $mapper->getEntriesBy();
                foreach ($mmp as $model) {
                    $model = $mapper->makeLatLng($model);
                    $mapper->save($model);
                }
                // no break
            case "1.3.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `name` = '%s', `description` = '%s' WHERE `key` = 'membermap' AND `locale` = '%s';", $value['name'], $value['description'], $key));
                }

                // Update icon
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");

                // Change datatype of column "user_id".
                $this->db()->query('ALTER TABLE `[prefix]_membermap` MODIFY COLUMN `user_id` INT(11) UNSIGNED NOT NULL;');

                // Add constraint to membermap after deleting orphaned rows in it (rows with an user_id that doesn't exist in the users table) as this would lead to an error.
                $existingUserIds = $this->db()->select('id')
                    ->from('users')
                    ->execute()
                    ->fetchList();

                $userIds = $this->db()->select('user_id')
                    ->from('membermap')
                    ->execute()
                    ->fetchList();

                $orphanedRows = array_diff($userIds ?? [], $existingUserIds ?? []);
                if (count($orphanedRows) > 0) {
                    $this->db()->delete()->from('membermap')
                        ->where(['user_id' => $orphanedRows])
                        ->execute();
                }

                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD INDEX `FK_[prefix]_membermap_[prefix]_users` (`user_id`) USING BTREE;');
                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD CONSTRAINT `FK_[prefix]_membermap_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;');
        }
    }
}
