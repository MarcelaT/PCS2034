<?php

namespace TipoMissao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use TipoMissao\Model\TipoMissao;
use TipoMissao\Form\TipoMissaoForm;
use TipoMissao\Form\TipoMissaoFilterForm;

class TipoMissaoController extends AbstractActionController
{
	protected $tipoMissaoTable;

	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$form = new TipoMissaoFilterForm();
		$form->get('submit')->setValue('Filtrar');
		
		$tipoMissoes = $this->getTipoMissaoTable()->fetchAll();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega os campos do filtro
				$nome = $request->getPost('nome');
				$descricao = $request->getPost('descricao');
				
				// preenche a lista filtrada de usuários
				$tipoMissoes = $this->getTipoMissaoTable()->getTipoMissaoFiltered($nome, $descricao);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'tipoMissoes' => $tipoMissoes,
		));
	}

	public function addAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');		
		
		$form = new TipoMissaoForm();
		$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$tipoMissao = new TipoMissao();
			$form->setInputFilter($tipoMissao->getInputFilter());
			$form->setData($request->getPost());
			
			$submit = $request->getPost('submit');
			if ($submit == 'Adicionar' && $form->isValid()) {
				$tipoMissao->exchangeArray($form->getData());
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$tipoMissao->dataCriacao = $dataAtual;
				$this->getTipoMissaoTable()->saveTipoMissao($tipoMissao);
			}
			
			// Redirect to list of tipomissao
			return $this->redirect()->toRoute('tipomissao');
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('tipomissao', array('action' => 'add'));
		}

		// recupera o tipo de missao pelo id
		try {
			$tipoMissao = $this->getTipoMissaoTable()->getTipoMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('tipomissao', array('action' => 'index'));
		}

		$form  = new TipoMissaoForm();
		$form->bind($tipoMissao);
		$form->get('submit')->setAttribute('value', 'Editar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($tipoMissao->getInputFilter());
			$form->setData($request->getPost());

			$submit = $request->getPost('submit');
			if ($submit == 'Editar' && $form->isValid()) {
				$this->getTipoMissaoTable()->saveTipoMissao($tipoMissao);
			}
			
			// Redirect to list of tipomissao
			return $this->redirect()->toRoute('tipomissao');
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
			return $this->redirect()->toRoute('tipomissao');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getTipoMissaoTable()->deleteTipoMissao($id);
			}

			// Redirect to list of tipomissao
			return $this->redirect()->toRoute('tipomissao');
		}

		return array(
			'id' => $id,
			'tipoMissao' => $this->getTipoMissaoTable()->getTipoMissao($id)
		);
	}
	
	////////////
	// Tables //
	////////////
	public function getTipoMissaoTable()
	{
		if (!$this->tipoMissaoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
		}
		return $this->tipoMissaoTable;
	}
}
