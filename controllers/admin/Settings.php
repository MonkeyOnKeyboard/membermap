<?php

namespace Modules\Membermap\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuGmap',
                'active' => false,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'gmembermap',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gmembermap'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['controller' => 'settings', 'action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('map_apikey', $this->getRequest()->getPost('apiKey'));

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('apiKey', (string)$this->getConfig()->get('map_apikey'));
    }
}
