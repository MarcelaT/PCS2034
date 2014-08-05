<?php

namespace Missao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Missao\Model\Missao;
use Missao\Form\MissaoForm;

use Missao\Model\Recurso;
use Missao\Model\RecursoNome;

use Missao\Form\MissaoStatusForm;

class MissaoController extends AbstractActionController
{
	protected $missaoTable;
	protected $tipoMissaoTable;
	protected $recursoTable;
	protected $tipoRecursoTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		return new ViewModel(array(
			'Missoes' => $this->getMissaoTable()->fetchAll(),
			'permissao' => $this->commonsPlugin()->getPermissaoUsuario(),
		));
	}

	public function alocarrecursosAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		if (!$idMissao) {
			return $this->redirect()->toRoute('missao');
		}
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('missao');
			}
			
			$total = $request->getPost('total');
			
			//if ($submit == 'Editar' && $form->isValid()) {
			for ($i = 1; $i <= $total; $i++) {

				$Recurso = new Recurso();
				$quantidade = "quantidade".$i;
				$idTipoRecurso = "id".$i;

				$Recurso->quantidade = $request->getPost($quantidade);
				$Recurso->idTipoRecurso = $request->getPost($idTipoRecurso);	
				$Recurso->idMissao = $idMissao;

				try {
					$Missao = $this->getMissaoTable()->getMissao($idMissao);
				} catch (\Exception $ex) {
					return $this->redirect()->toRoute('missao', array('action' => 'index'));
				}

				$Missao->recursosAlocados = true;
				$this->getMissaoTable()->saveMissao($Missao);
				if ($Recurso->quantidade != 0) {
					$this->getRecursoTable()->saveRecurso($Recurso);
				}
			}
			//}

			// Retorna para a lista de missões
			return $this->redirect()->toRoute('missao');
		}
		
		$tipoRecursos = $this->getTipoRecursoTable()->fetchAll();
		$total = count($tipoRecursos);

		return array('tipoRecursos' => $tipoRecursos, 'idMissao'=> $idMissao, 'total'=> $total);
	}

	public function detalhesAction() {
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		
		try {
			$Missao = $this->getMissaoTable()->getMissao($idMissao);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}

		try {
			$TipoMissao = $this->getTipoMissaoTable()->getTipoMissao($Missao->idTipoMissao);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}

		$Recursos = $this->getRecursoTable()->getRecursosidMissao($idMissao);
		$recursosLista = array();
		foreach($Recursos as $recurso){
			$tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($recurso->idTipoRecurso);
			$RecursoNome = new RecursoNome();
			$RecursoNome->quantidade = $recurso->quantidade;
			$RecursoNome->nome = $tipoRecurso->nome;
			array_push($recursosLista, $RecursoNome);
		}

		return array(
			'recursosLista' => $recursosLista,
			'Missao' => $Missao,
			'TipoMissao' => $TipoMissao,
		);
	}
	
	public function deleteAction()
	{
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
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
		$usuarios = array();
		array_push($usuarios, 'lider_missao');
		$this->commonsPlugin()->verificaPermissao($usuarios);

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
		$usuarios = array();
		array_push($usuarios, 'lider_missao');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
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
		$usuarios = array();
		array_push($usuarios, 'lider_missao');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'concluida');
	}
	
	public function statusabortadaAction()
	{
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'lider_missao');
		$this->commonsPlugin()->verificaPermissao($usuarios);
				
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'abortada');
	}
	
	public function getMissaoTable()
	{
		if (!$this->missaoTable) {
			$sm = $this->getServiceLocator();
			$this->missaoTable = $sm->get('Missao\Model\MissaoTable');
		}
		return $this->missaoTable;
	}
	
	public function getTipoMissaoTable()
	{
		if (!$this->tipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
		}
		return $this->tipoMissaoTable;
	}
	
	public function getRecursoTable()
	{
		if (!$this->recursoTable) {
			$sm = $this->getServiceLocator();
			$this->recursoTable = $sm->get('Missao\Model\RecursoTable');
		}
		return $this->recursoTable;
	}
	
	public function getTipoRecursoTable()
	{
		if (!$this->tipoRecursoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoRecursoTable = $sm->get('TipoRecurso\Model\TipoRecursoTable');
		}
		return $this->tipoRecursoTable;
	}
	
}
