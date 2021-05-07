<?php

namespace Modules\Membermap\Controllers;

use \Modules\Membermap\Mappers\MemberMap as MemberMapMapper;
use \Modules\Membermap\Models\MemberMap as MemberMapModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
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
            $model->setUser_Id($this->getUser()->getId());
        }
        $this->getView()->set('membermap', $model);

        if ($this->getRequest()->isPost() ) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'city' => 'required',
                'zip_code' => 'required',
                'country_code' => 'required'
            ]);

            if ($validation->isValid()) {
                $model->setCity($this->getRequest()->getPost('city'))
                    ->setZip_code($this->getRequest()->getPost('zip_code'))
                    ->setCountry_code($this->getRequest()->getPost('country_code'));

                $mapper->save($model);

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
            $mapper = new MemberMapMapper();

            $mapper->deleteUser($this->getRequest()->getParam('user_id'));

            $this->redirect()
            ->withMessage('deleteSuccess')
            ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }
}

