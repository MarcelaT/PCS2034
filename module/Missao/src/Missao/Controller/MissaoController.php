<?php

namespace Missao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Missao\Model\Missao;
use Missao\Form\MissaoForm;
use Missao\Form\AlocacaoRecursosForm;

class MissaoController extends AbstractActionController
{
	protected $MissaoTable;
	protected $TipoMissaoTable;

	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		return new ViewModel(array('Missoes' => $this->getMissaoTable()->fetchAll()));
	}

	public function addAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$form = new MissaoForm();
		$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$Missao = new Missao();
			$form->setInputFilter($Missao->getInputFilter());
			$form->setData($request->getPost());
			
			$submit = $request->getPost('submit');
			if ($submit == 'Adicionar' && $form->isValid()) {
				$Missao->exchangeArray($form->getData());
				$this->getMissaoTable()->saveMissao($Missao);
			}
			
			// Redirect to list of Missao
			return $this->redirect()->toRoute('missao');
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action' => 'add'));
		}

		// recupera missao pelo id
		try {
			$Missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}

		$form  = new MissaoForm();
		$form->bind($Missao);
		$form->get('submit')->setAttribute('value', 'Editar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($Missao->getInputFilter());
			$form->setData($request->getPost());

			$submit = $request->getPost('submit');
			if ($submit == 'Editar' && $form->isValid()) {
				$this->getMissaoTable()->saveMissao($Missao);
			}
			
			// Redirect to list of tipomissao
			return $this->redirect()->toRoute('missao');
		}

		return array(
			'id' => $id,
			'form' => $form,
		);
	}

	public function deleteAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getMissaoTable()->deleteMissao($id);
			}

			// Redirect to list of Missao
			return $this->redirect()->toRoute('missao');
		}

		return array(
			'id' => $id,
			'Missao' => $this->getMissaoTable()->getMissao($id)
		);
	}
	
	public function getMissaoTable()
	{
		if (!$this->MissaoTable) {
			$sm = $this->getServiceLocator();
			$this->MissaoTable = $sm->get('Missao\Model\MissaoTable');
		}
		return $this->MissaoTable;
	}

	public function alocarrecursosAction()
	{
		$form = new AlocacaoRecursosForm();
		$form->get('submit')->setValue('Adicionar');
		return new ViewModel(array('tipoRecursos' => $this->getTipoRecursoTable()->fetchAll(), 'form'=> $form));
	}

	public function getTipoRecursoTable()
	{
		//if (!$this->tipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoMissaoTable = $sm->get('TipoRecurso\Model\TipoRecursoTable');
		//}
		return $this->tipoMissaoTable;
	}

}
