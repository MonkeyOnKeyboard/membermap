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

            
            
    }
    
    
    public function mapAction()
    {
        
        $this->getLayout()->getHmenu()
        ->add($this->getTranslator()->trans('menuGmap'), ['action' => 'map']);
        
        $mapper = new GmapMapper();
        
        
        $this->getView()->set('memberlocations', $mapper->getMmp());
        $this->getView()->set('apiKey', (string)$this->getConfig()->get('map_apikey'));
    }
    
    
    public function treatAction()
    {
        $gmapMapper = new GmapMapper();
        
        if (isset ($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = '';
        }
        
        if ($gmapMapper->getMmp() != ''){
        $this->getView()->set('membermap', $gmapMapper->getMmapByID($user_id));
        }
                        
        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'city' => 'required',
                'zip_code' => 'required',
                'country_code' => 'required'
            ]);
            
                           
                if ($validation->isValid()) {
                    
                    
                    
                    $gmapModel = new GmapModel();
                    $gmapModel
                    ->setUser_Id($user_id)
                    ->setCity($this->getRequest()->getPost('city'))
                    ->setZip_code($this->getRequest()->getPost('zip_code'))
                    ->setCountry_code($this->getRequest()->getPost('country_code'));
                    
                    $gmapMapper->save($gmapModel);
                    
                    $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
                }
                
                if ($this->getRequest()->getParam('id')) {
                    $redirect = ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')];
                } else {
                    $redirect = ['action' => 'treat'];
                }
                
            }
           
            
        }
        
}

