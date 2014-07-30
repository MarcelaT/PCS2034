<?php

namespace Missao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Missao\Model\Missao;
use Missao\Form\MissaoForm;
use Missao\Model\Recurso;
use Missao\Model\RecursoNome;

use Missao\Form\AlocacaoRecursosForm;

class MissaoController extends AbstractActionController
{
	protected $MissaoTable;
	protected $TipoMissaoTable;
	protected $storage;
	protected $authservice;


	public function getAuthService() {
		if (!$this->authservice) {
			$this->authservice = $this->getServiceLocator()->get('AuthService');
		}
		return $this->authservice;
	}
	
	public function getSessionStorage() {
		if (!$this->storage) {
			$this->storage = $this->getServiceLocator()->get('SanAuth\Model\MyAuthStorage');
		}
		return $this->storage;
	}

	public function indexAction()
	{
		$permissao = '';
		
		// recupera o usuário
		$usuario = $this->getAuthService()->getStorage()->read('usuario');
		
		// verifica se existe um usuário para adicionar a permissao
		if ($usuario) {
			$permissao = $usuario->permissao;
		}
		
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'administrador', 'coordenador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
		return new ViewModel(array('Missoes' => $this->getMissaoTable()->fetchAll(),'permissao'=>$permissao));
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

		$usuarios = array();
		array_push($usuarios, 'coordenador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		//$form = new AlocacaoRecursosForm();
		//$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			//$form->setInputFilter($Missao->getInputFilter());
			//$form->setData($request->getPost());

			$total = $request->getPost('total');
			
			//if ($submit == 'Editar' && $form->isValid()) {
			for($i=1;$i<=$total;$i++){

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

				$Missao->recursosAlocados ="sim";
				$this->getMissaoTable()->saveMissao($Missao);
				if($Recurso->quantidade!=0){
					$this->getRecursoTable()->saveRecurso($Recurso);
				}
			}
			//}
			
			// Redirect to list of tipomissao
			return $this->redirect()->toRoute('missao');
		}
		
		$tipoRecursos = $this->getTipoRecursoTable()->fetchAll();
		$total = count($tipoRecursos);

		return new ViewModel(array('tipoRecursos' => $tipoRecursos, 'idMissao'=> $idMissao, 'total'=> $total));
	}

	public function detalhesAction(){


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
			$RecursoNome  = new RecursoNome();
			$RecursoNome -> quantidade = $recurso->quantidade;
			$RecursoNome -> nome = $tipoRecurso -> nome;
			array_push($recursosLista, $RecursoNome);

		}

		return new ViewModel(array('recursosLista' => $recursosLista, 'Missao' => $Missao, 'TipoMissao' => $TipoMissao));
	}

	public function getTipoRecursoTable()
	{
		//if (!$this->tipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoMissaoTable = $sm->get('TipoRecurso\Model\TipoRecursoTable');
		//}
		return $this->tipoMissaoTable;
	}

		public function getRecursoTable()
	{
		//if (!$this->tipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->recursoTable = $sm->get('Missao\Model\RecursoTable');
		//}
		return $this->recursoTable;
	}

	public function getTipoMissaoTable()
	{
			$sm = $this->getServiceLocator();
			$this->tipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
		
		return $this->tipoMissaoTable;
	}

}
