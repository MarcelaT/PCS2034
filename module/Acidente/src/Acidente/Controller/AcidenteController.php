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
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
        
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
	
	public function infoAction()
    {
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('acidente', array('action' => 'add'));
		}
		
        return array('acidente' => $this->getAcidenteTable()->getAcidente($id));
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
