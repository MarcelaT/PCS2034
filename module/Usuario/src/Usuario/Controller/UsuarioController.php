<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Usuario\Model\Usuario;
use Usuario\Form\UsuarioForm;

class UsuarioController extends AbstractActionController
{
	protected $usuarioTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		return new ViewModel(array('usuarios' => $this->getUsuarioTable()->fetchAll()));
	}

	public function addAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$form = new UsuarioForm();
		$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$usuario = new Usuario();
			$form->setInputFilter($usuario->getInputFilter());
			$form->setData($request->getPost());
			
			$submit = $request->getPost('submit');
			if ($submit == 'Adicionar' && $form->isValid()) {
				$usuario->exchangeArray($form->getData());
				$this->getUsuarioTable()->saveUsuario($usuario);
			}
			
			// Redirect to list of usuarios
			return $this->redirect()->toRoute('usuario');
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('usuario', array('action' => 'add'));
		}

		// recupera o usuário pelo id
		try {
			$usuario = $this->getUsuarioTable()->getUsuario($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('usuario', array('action' => 'index'));
		}

		$form  = new UsuarioForm();
		$form->bind($usuario);
		$form->get('submit')->setAttribute('value', 'Editar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($usuario->getInputFilter());
			$form->setData($request->getPost());

			$submit = $request->getPost('submit');
			if ($submit == 'Editar' && $form->isValid()) {
				$this->getUsuarioTable()->saveUsuario($usuario);
			}
			
			// Redirect to list of usuarios
			return $this->redirect()->toRoute('usuario');
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
			return $this->redirect()->toRoute('usuario');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getUsuarioTable()->deleteUsuario($id);
			}

			// Redirect to list of usuarios
			return $this->redirect()->toRoute('usuario');
		}

		return array(
			'id' => $id,
			'usuario' => $this->getUsuarioTable()->getUsuario($id)
		);
	}
	
	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Usuario\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}
}
