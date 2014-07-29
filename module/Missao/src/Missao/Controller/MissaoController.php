<?php

namespace Missao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Missao\Model\Missao;
use Missao\Form\MissaoForm;
use Missao\Form\MissaoStatusForm;
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
	
	public function missaoprotocoloAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');

		$form = new MissaoStatusForm();
		$request = $this->getRequest();
		$id = 0;
		
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('success');
			}

			$form->setData($request->getPost());
			if ($form->isValid()) {
				$protocolo = $request->getPost('protocolo');

				if ($protocolo == '') {
					$this->flashmessenger()->addMessage('Preencha o campo \'Protocolo\'.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				// recupera missao pelo protocolo
				try {
					$missao = $this->getMissaoTable()->getMissaoByProtocolo($protocolo);
				} catch (\Exception $ex) {
					$this->flashmessenger()->addMessage('\'Protocolo\' inválido ou inexistente.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				// verifica se a missão está em estado transitório
				if ($missao->status != 'em_andamento') {
					$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				$id = $missao->id;
				
				// Redirect para a página de seleção do status
				return $this->redirect()->toRoute('missao', array(
					'action' => 'atualizarstatus',
					'id' => $missao->id,
				));
			}
		}

		return array(
			'form' => $form,
			'messages' => $this->flashmessenger()->getMessages(),
			'id' => $id,
		);
	}
	
	public function atualizarstatusAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// recupera missao pelo id
		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// verifica se a missão está em estado transitório
		if ($missao->status != 'em_andamento') {
			$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		$tipoMissao = $this->getTipoMissaoTable()->getTipoMissao($missao->idTipoMissao);
		
		return array(
			'id' => $id,
			'missao' => $missao,
			'tipoMissao' => $tipoMissao->nome,
		);
	}
	
	public function updateStatus($id, $status)
	{
		// recupera a missao pelo id
		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// verifica se a missão está em estado transitório
		if ($missao->status != 'em_andamento') {
			$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// muda o status
		try {
			$missao = $this->getMissaoTable()->atualizarStatusMissao($id, $status);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return array(
			'id' => $id,
		);
	}
	
	public function statusconcluidaAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'concluida');
	}
	
	public function statusabortadaAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'abortada');
	}
	
	public function getMissaoTable()
	{
		if (!$this->MissaoTable) {
			$sm = $this->getServiceLocator();
			$this->MissaoTable = $sm->get('Missao\Model\MissaoTable');
		}
		return $this->MissaoTable;
	}
	
	public function getTipoMissaoTable()
	{
		if (!$this->TipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->TipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
		}
		return $this->TipoMissaoTable;
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
