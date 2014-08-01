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
        // salva a permissão no layout
        $this->commonsPlugin()->setPermissaoLayout();
        
		return new ViewModel(array(
             'acidentes' => $this->getAcidenteTable()->fetchAll(),
         ));
    }

    public function addAction()
    {
        // verifica a permissão do usuário
        $this->commonsPlugin()->verificaPermissao('coordenador');
		
		$form = new AcidenteForm();
        $form->get('submit')->setValue('Adicionar');
		
        $request = $this->getRequest();
        if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
            $submit = $request->getPost('submit');
            if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('acidente');
            }

            $acidente = new Acidente();
            $form->setInputFilter($acidente->getInputFilter());
            $form->setData($request->getPost());

            if ($submit == 'Adicionar' && $form->isValid()) {
                $acidente->exchangeArray($form->getData());
                date_default_timezone_set("Brazil/East");
                $dataAtual = date('Y-m-d H:i:s');
                $acidente->data = $dataAtual;
                $this->getAcidenteTable()->saveAcidente($acidente);
            }
			
            // Redirect to list of acidentes
            return $this->redirect()->toRoute('acidente');
        }
        return array('form' => $form);
    }
	
	public function getAcidenteInfoAction()
    {
		// verifica a permissão do usuário
        $this->commonsPlugin()->verificaPermissao('coordenador');
		
        return $this->redirect()->toRoute('info-acidente');
    }
	
	public function getAcidenteTable()
    {
         if (!$this->acidenteTable) {
             $sm = $this->getServiceLocator();
             $this->acidenteTable = $sm->get('Acidente\Model\AcidenteTable');
         }
         return $this->acidenteTable;
    }
	
}
