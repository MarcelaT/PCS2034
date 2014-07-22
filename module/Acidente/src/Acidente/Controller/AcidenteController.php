<?php

namespace Acidente\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Acidente\Model\Acidente;
use Acidente\Form\AcidenteForm;

class AcidenteController extends AbstractActionController
{
	protected $acidenteTable;
	
    public function indexAction()
    {
		return new ViewModel(array(
             'acidentes' => $this->getAcidenteTable()->fetchAll(),
         ));
    }

    public function addAcidenteAction()
    {
		$form = new AcidenteForm();
        $form->get('submit')->setValue('Add');
		
        $request = $this->getRequest();
        if ($request->isPost()) {
            $acidente = new Acidente();
            $form->setInputFilter($acidente->getInputFilter());
            $form->setData($request->getPost());
			
            if ($form->isValid()) {
                $acidente->exchangeArray($form->getData());
                $this->getAcidenteTable()->saveAcidente($acidente);
				 // Redirect to list of acidentes
                return $this->redirect()->toRoute('add-acidente');
            }
        }
        return array('form' => $form);
    }
	
	public function getAcidenteTable()
    {
         if (!$this->acidenteTable) {
             $sm = $this->getServiceLocator();
             $this->acidenteTable = $sm->get('Acidente\Model\AcidenteTable');
         }
         return $this->acidenteTable;
    }

    public function getAcidenteInfoAction()
    {
        return $this->redirect()->toRoute('info-acidente');
    }
}
