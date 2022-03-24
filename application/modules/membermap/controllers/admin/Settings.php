<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuMemberMap',
                'active' => false,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuAdressen',
                'active' => false,
                'icon' => 'fas fa-map-marked-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'adressen', 'action' => 'index']),
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
            'membermap',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('membermap'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['controller' => 'settings', 'action' => 'index']);

        if ($this->getRequest()->isPost() ) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'service' => 'required|numeric|integer|min:1|max:2',
                'apiKey' => 'required',
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('map_service', $this->getRequest()->getPost('service'));
                $this->getConfig()->set('map_apikey', $this->getRequest()->getPost('apiKey'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('service', (string)$this->getConfig()->get('map_service'));
        $this->getView()->set('apiKey', (string)$this->getConfig()->get('map_apikey'));
    }
}
