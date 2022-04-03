<?php
/**
 * @copyright MonkeyOnKeyboard
 * @package ilch
 */

namespace Modules\Membermap\Controllers\Admin;

use \Modules\Membermap\Mappers\MemberMap as MemberMapMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuMemberMap',
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
            'membermap',
            $items
        );
    }

    public function indexAction()
    {
        $mapper = new MemberMapMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuMemberMap'), ['controller' => 'index', 'action' => 'index']);

        $this->getView()->set('memberlocations', $mapper->getMmpEmpty());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $mapMapper = new MemberMapMapper();
            $mapMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }

}