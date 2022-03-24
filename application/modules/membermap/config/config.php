<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Config;

class Config extends \Ilch\Config\Install
{
    /**
     * @todo description ändern / erweitern
     */
    public $config = [
        'key' => 'membermap',
        'version' => '1.3.0',
        'icon_small' => 'fa-map-marked-alt',
        'author' => 'MonkeyOnKeyboard',
        'languages' => [
            'de_DE' => [
                'name' => 'Membermap',
                'description' => 'Eine Membermap.',
            ],
            'en_EN' => [
                'name' => 'Membermap',
                'description' => 'A Membermap.',
            ],
        ],

        'ilchCore' => '2.1.42',
        'phpVersion' => '7.0'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('map_service', 0)
            ->set('map_apikey', '');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_membermap`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'map_apikey';");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'map_service';");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_membermap` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NULL DEFAULT NULL,
            `zip_code` VARCHAR(15) NULL DEFAULT NULL,
            `street` VARCHAR(255) NULL DEFAULT NULL,
            `city` VARCHAR(100) NULL DEFAULT NULL,
            `country_code` VARCHAR(4) NULL DEFAULT NULL,
            `lat` VARCHAR(255) NULL DEFAULT NULL,
            `lng` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0.0":
                // Map-Service auswahl (1 = MapQuest, 2 = Google)
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('map_service', '1');
            case "1.1.0":
                // Add Colum street for optional Street entry
                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD COLUMN `street` VARCHAR(255) NULL DEFAULT NULL AFTER `zip_code`;');
/*             case "1.2.0":
                // Update description
                foreach($this->config['languages'] as $key => $value) {
                    $this->db()->query(sprintf("UPDATE `[prefix]_modules_content` SET `name` = '%s', `description` = '%s' WHERE `key` = 'membermap' AND `locale` = '%s';", $value['name'], $value['description'], $key));
                } */
            case "1.3.0":
                // Add Colum lat and lng for storing locations latLng
                $this->db()->query('ALTER TABLE `[prefix]_membermap` ADD COLUMN `lat` VARCHAR(255) NULL DEFAULT NULL AFTER `country_code`, ADD COLUMN `lng` VARCHAR(255) NULL DEFAULT NULL AFTER `lat`;');
        }
    }
}
