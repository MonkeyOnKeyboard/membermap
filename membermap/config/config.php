<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Membermap\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'membermap',
        'version' => '1.0.0',
        'icon_small' => 'fa-map-marked-alt',
        'author' => 'MonkeyOnKeyboard',
        'languages' => [
            'de_DE' => [
                'name' => 'Membermap',
                'description' => 'Eine Membermap.',
            ],
            'en_EN' => [
                'name' => 'membermap',
                'description' => 'A membermap',
            ],
        ],
        
        'ilchCore' => '2.1.42',
        'phpVersion' => '7.0'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('map_apikey', '');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_membermap`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'map_apikey';");
    }
    
    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_membermap` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NULL DEFAULT NULL,
            `zip_code` INT(11) NULL DEFAULT NULL,
            `city` VARCHAR(100) NULL DEFAULT NULL,
            `country_code` VARCHAR(4) NULL DEFAULT NULL,
            PRIMARY KEY(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0.0":
                
        }
    }
}
