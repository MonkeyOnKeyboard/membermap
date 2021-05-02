<?php

namespace Modules\Membermap\Controllers;

use \Modules\Membermap\Mappers\Gmap as GmapMapper;
use \Modules\Membermap\Models\Gmap as GmapModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuGmap'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGmap'), ['controller' => 'index', 'action' => 'index']);

    }

    public function mapAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuGmap'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGmap'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('mapView'), ['controller' => 'index', 'action' => 'map']);

        $mapper = new GmapMapper();


        $this->getView()->set('memberlocations', $mapper->getMmp());
        $this->getView()->set('apiKey', (string)$this->getConfig()->get('map_apikey'));
    }

    public function treatAction()
    {
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuGmap'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGmap'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('mapEntry'), ['controller' => 'index', 'action' => 'treat']);

        if (!$this->getUser()) {
            $this->redirect()
                ->withMessage('loginRequired')
                ->to(['controller' => 'index', 'action' => 'index']);
        }

        $gmapMapper = new GmapMapper();

        $gmapModel = $gmapMapper->getMmapByID($this->getUser()->getId());
        if (!$gmapModel) {
            $gmapModel = new GmapModel();
            $gmapModel->setUser_Id($this->getUser()->getId());
        }
        $this->getView()->set('membermap', $gmapModel);

        if ($this->getRequest()->isPost() ) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'city' => 'required',
                'zip_code' => 'required',
                'country_code' => 'required'
            ]);

            if ($validation->isValid()) {
                $gmapModel->setCity($this->getRequest()->getPost('city'))
                    ->setZip_code($this->getRequest()->getPost('zip_code'))
                    ->setCountry_code($this->getRequest()->getPost('country_code'));

                $gmapMapper->save($gmapModel);

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
        if ($this->getRequest()->isSecure()) {
            $gmapMapper = new GmapMapper();
            
                       
            $gmapMapper->deleteUser($this->getRequest()->getParam('user_id'));
            
            $this->redirect()
            ->withMessage('deleteSuccess')
            ->to(['action' => 'index']);
        }
        
        $this->redirect(['action' => 'index']);
    }
    

}

