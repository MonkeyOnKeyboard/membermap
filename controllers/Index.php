<?php

/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Controllers;

use Ilch\Controller\Frontend;
use Modules\Membermap\Mappers\MemberMap as MemberMapMapper;
use Modules\Membermap\Models\MemberMap as MemberMapModel;
use Ilch\Validation;

class Index extends Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('membermap'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('membermap'), ['controller' => 'index', 'action' => 'index']);
    }

    public function mapAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('membermap'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('membermap'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('mapView'), ['controller' => 'index', 'action' => 'map']);

        $mapper = new MemberMapMapper();

        $apiKey = (string)$this->getConfig()->get('map_apikey');
        if ($apiKey) {
            if ((int)$this->getConfig()->get('map_service') === 1) {
                $this->getRequest()->setActionName('mapMapQuest');
            } elseif ((int)$this->getConfig()->get('map_service') === 2) {
                $this->getRequest()->setActionName('mapGoogle');
            } elseif ((int)$this->getConfig()->get('map_service') === 3) {
                $this->getRequest()->setActionName('mapOsm');
            }
            $this->getView()->set('memberlocations', $mapper->getMmp());
        }
        $this->getView()->set('apiKey', $apiKey);
    }

    public function treatAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('membermap'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('membermap'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('mapEntry'), ['controller' => 'index', 'action' => 'treat']);

        if (!$this->getUser()) {
            $this->redirect()
                ->withMessage('loginRequired')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        $mapper = new MemberMapMapper();

        $model = $mapper->getMmapByID($this->getUser()->getId());
        if (!$model) {
            $model = new MemberMapModel();
            $model->setUserId($this->getUser()->getId());
        }
        $this->getView()->set('membermap', $model);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'street'        => 'required',
                'city'          => 'required',
                'zip_code'      => 'required',
                'country_code'  => 'required'
            ]);

            if ($validation->isValid()) {
                
                $street=$this->getRequest()->getPost('street');
                $postalCode=$this->getRequest()->getPost('zip_code');
                $city=$this->getRequest()->getPost('city');
                $countryCode=$this->getRequest()->getPost('country_code');

                /*
                $model->setStreet($this->getRequest()->getPost('street'))
                    ->setCity($this->getRequest()->getPost('city'))
                    ->setZipcode($this->getRequest()->getPost('zip_code'))
                    ->setCountryCode($this->getRequest()->getPost('country_code'));
                */
                if ((int)$this->getConfig()->get('map_service') === 2) {

                    /*
                    echo "<hr>";
                    echo '<pre>';
                    print_r($street." | ".$postalCode." | ".$city." | ".$countryCode);
                    echo '</pre>';
                    */

                    $apiKey = (string)$this->getConfig()->get('map_apikey');

                    $address = "{$street}, {$postalCode} {$city}, {$countryCode}";
                    
                    /*
                    echo "<hr>";
                    echo '<pre>';
                    print_r($address);
                    echo '</pre>';
                    */

                    $coords = $mapper->getLatLngFromAddress($address, $apiKey);
                    
                    /*
                    echo "<hr>";
                    echo '<pre>';
                    print_r($coords);
                    echo '</pre>';
                    */

                    $model->setStreet($street)
                         ->setCity($city)
                        ->setZipcode($postalCode)
                        ->setCountryCode($countryCode)
                        ->setLat($coords['lat'])
                        ->setLng($coords['lng']);
                    /*    
                    echo "<hr>";
                    echo '<pre>';
                    print_r($model);
                    echo '</pre>';
                    die();
                    */
                    $mapper->save($model);


                } else {
                    $apiKey = (string)$this->getConfig()->get('map_apikey');
                    $address = "{$street}, {$postalCode} {$city}, {$countryCode}";
                    $coords = $mapper->getCoordinatesFromAddress($address, $apiKey);
                    
                    $model->setStreet($street)
                         ->setCity($city)
                        ->setZipcode($postalCode)
                        ->setCountryCode($countryCode)
                        ->setLat($coords['lat'])
                        ->setLng($coords['lon']);

                    //$model = $mapper->makeLatLng($model);
                    
                    $mapper->save($model);
                }
                

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('user_id')) {
            $mapper = new MemberMapMapper();

            $mapper->deleteUser($this->getRequest()->getParam('user_id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }
}
