<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Controllers\Admin;

use \Modules\Membermap\Mappers\MemberMap as MemberMapMapper;
use \Modules\Membermap\Models\MemberMap as MemberMapModel;
use Ilch\Validation;

class Adressen extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuMemberMap',
                'active' => false,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
            ],
            [
                'name' => 'menuAdressen',
                'active' => true,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'adressen', 'action' => 'index']),
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'membermap',
            $items
        );
    }

    public function indexAction()
    {

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAdressen'), ['controller' => 'adressen', 'action' => 'index']);

                $mapper = new MemberMapMapper();
                
                if ((int)$this->getConfig()->get('map_service') === 1) {
                    $this->getView()->set('memberlocations', $mapper->getMmp());
                } elseif ((int)$this->getConfig()->get('map_service') === 2) {
                    $this->addMessage('enablemapquest', 'info');
                }
                
                
    }

    public function updateLatLngAction()
    {
        
        $mapper = new MemberMapMapper();
        
        $apiKey = (string)$this->getConfig()->get('map_apikey');
        if ($apiKey) {
            if ((int)$this->getConfig()->get('map_service') === 1) {
                $this->getRequest()->setActionName('mapMapQuest');
            } elseif ((int)$this->getConfig()->get('map_service') === 2) {
                $this->getRequest()->setActionName('mapGoogle');
            }
            $this->getView()->set('memberlocations', $mapper->getMmp());
        }

        
        
        $array = $mapper->getMmapByID($this->getRequest()->getParam('user_id'));
        //var_dump($array);
        

        
           
        if ($array->getStreet() != "") {
            $address			=	$array->getStreet().', '.$array->getCity();;
        } else {
            $address			=	$array->getCity();
        }
        $address			=	strtolower($address);
        $address			=	str_replace(array('ä','ü','ö','ß'), array('ae', 'ue', 'oe', 'ss'), $address );
        $address			=	preg_replace("/[^a-z0-9\_\s]/", "", $address);
        $address			=	str_replace( array(' ', '--'), array('-', '-'), $address );
        $zip_code         =   $array->getZip_code();
        $country_code = $array->getCountry_code();
        
        
        $jsonurl = "https://www.mapquestapi.com/geocoding/v1/address?key=$apiKey&location=$address,$zip_code,$country_code";
        
        $json = file_get_contents($jsonurl);
        $output = json_decode($json, true);
        
       
        $latitude=$output['results'][0]['locations'][0]['latLng']['lat'];
        $longitude=$output['results'][0]['locations'][0]['latLng']['lng'];
        
        
        $model = new MemberMapModel();
        $model->setUser_Id($this->getRequest()->getParam('user_id'));
        
        $model->setLat($latitude)
        ->setLng($longitude);
        $mapper->saveLatLng($model);
        
        $this->redirect()
        ->withMessage('saveSuccess')
        ->to(['action' => 'index']);
        
        
    }
    
}
