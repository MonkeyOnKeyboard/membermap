<?php

namespace Modules\Membermap\Controllers;

use \Modules\Membermap\Mappers\Gmap as GmapMapper;
use \Modules\Membermap\Models\Gmap as GmapModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuGmap'), ['action' => 'index']);

            $mapper = new GmapMapper();
            
            
            $this->getView()->set('memberlocations', $mapper->getMmp());
            $this->getView()->set('apiKey', (string)$this->getConfig()->get('map_apikey'));
    }

    
}
