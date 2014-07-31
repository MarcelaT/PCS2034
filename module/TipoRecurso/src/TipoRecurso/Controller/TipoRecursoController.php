<?php

namespace TipoRecurso\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use TipoRecurso\Model\TipoRecurso;
use TipoRecurso\Form\TipoRecursoForm;

class TipoRecursoController extends AbstractActionController
{
	protected $tipoRecursoTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
				$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
		$form = new TipoRecursoForm();
		$form->get('submit')->setValue('Filtrar');
		
		$tipoRecursos = $this->getTipoRecursoTable()->fetchAll();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega os campos do filtro
				$nome = $request->getPost('nome');
				
				// preenche a lista filtrada de usuários
				$tipoRecursos = $this->getTipoRecursoTable()->getTipoRecursoFiltered($nome);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'tipoRecursos' => $tipoRecursos,
		));	}

	public function addAction()
	{
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
		$form = new TipoRecursoForm();
		$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$tipoRecurso = new TipoRecurso();
			$form->setInputFilter($tipoRecurso->getInputFilter());
			$form->setData($request->getPost());
			
			$submit = $request->getPost('submit');
			if ($submit == 'Adicionar' && $form->isValid()) {
				$tipoRecurso->exchangeArray($form->getData());
				$this->getTipoRecursoTable()->saveTipoRecurso($tipoRecurso);
			}
			
			// Redirect to list of tiporecurso
			return $this->redirect()->toRoute('tiporecurso');
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('tiporecurso', array('action' => 'add'));
		}

		// recupera o Tipo de Recurso pelo id
		try {
			$tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('tiporecurso', array('action' => 'index'));
		}

		$form  = new TipoRecursoForm();
		$form->bind($tipoRecurso);
		$form->get('submit')->setAttribute('value', 'Editar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($tipoRecurso->getInputFilter());
			$form->setData($request->getPost());

			$submit = $request->getPost('submit');
			if ($submit == 'Editar' && $form->isValid()) {
				//$tipoRecurso->id = 1;

				$this->getTipoRecursoTable()->saveTipoRecurso($tipoRecurso);
			}
			
			// Redirect to list of tiporecurso
			return $this->redirect()->toRoute('tiporecurso');
		}

		return array(
			'id' => $id,
			'form' => $form,
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
			return $this->redirect()->toRoute('tiporecurso');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getTipoRecursoTable()->deleteTipoRecurso($id);
			}

			// Redirect to list of tiporecurso
			return $this->redirect()->toRoute('tiporecurso');
		}

		return array(
			'id' => $id,
			'tipoRecurso' => $this->getTipoRecursoTable()->getTipoRecurso($id)
		);
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
