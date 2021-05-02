<?php

namespace Modules\Membermap\Controllers\Admin;

use \Modules\Membermap\Mappers\Gmap as GmapMapper;
use \Modules\Membermap\Models\Gmap as GmapModel;
use Ilch\Validation;


class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuGmap',
                'active' => true,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
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
            'gmembermap',
            $items
        );
    }

    public function indexAction()
    {
        $mapper = new GmapMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gmembermap'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuGmap'), ['controller' => 'index', 'action' => 'index']);

        $this->getView()->set('memberlocations', $mapper->getMmpEmpty());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $mapMapper = new GmapMapper();
            $mapMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }

}
