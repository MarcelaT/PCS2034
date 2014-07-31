<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Usuario\Model\Usuario;
use Usuario\Form\UsuarioFilterForm;
use Usuario\Form\UsuarioAddForm;
use Usuario\Form\UsuarioEditForm;
use Usuario\Form\UsuarioEditDadosForm;
use Usuario\Form\UsuarioPassForm;

class UsuarioController extends AbstractActionController
{
	protected $usuarioTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário

		$usuarios1 = array();
		array_push($usuarios1, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios1);

		$form = new UsuarioFilterForm();
		$form->get('submit')->setValue('Filtrar');
		
		$usuarios = $this->getUsuarioTable()->fetchAll();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega os campos do filtro
				$login = $request->getPost('login');
				$nome = $request->getPost('nome');
				$email = $request->getPost('email');
				$permissao = $request->getPost('permissao');

				// preenche a lista filtrada de usuários
				$usuarios = $this->getUsuarioTable()->getUsuariosFiltered($login, $nome, $email, $permissao);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'usuarios' => $usuarios,
		));
	}
	
	public function addAction()
	{
		// verifica a permissão do usuário
		$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);
		
		$form = new UsuarioAddForm();
		$form->get('submit')->setValue('Adicionar');
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('usuario');
			}
			
			$usuario = new Usuario();
			$form->setInputFilter($usuario->getInputFilter());
			$form->setData($request->getPost());
			
			if ($submit == 'Adicionar' && $form->isValid() && $request->getPost('senha') != '') {
				$usuario->exchangeArray($form->getData());
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$usuario->dataCriacao = $dataAtual;
				$usuario->dataEdicao = $dataAtual;
				$usuario->senha = md5($request->getPost('senha'));
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
		$usuarios = array();
		array_push($usuarios, 'administrador');
		$this->commonsPlugin()->verificaPermissao($usuarios);

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

		// valores que não aparecem explicitamente no Form
		$senha = $usuario->senha;
		$dataCriacao = $usuario->dataCriacao;
		
		$form  = new UsuarioEditForm();
		$form->get('submit')->setValue('Editar');
		$form->bind($usuario);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('usuario');
			}
			
			$form->setInputFilter($usuario->getInputFilter());
			$form->setData($request->getPost());
			
			if ($submit == 'Editar' && $form->isValid()) {
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$usuario->dataEdicao = $dataAtual;
				$usuario->dataCriacao = $dataCriacao;
				$usuario->senha = $senha;
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
	
	public function editdadosAction()
	{
		// salva a permissão no layout
		$this->commonsPlugin()->setPermissaoLayout();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('success');
		}

		// recupera o usuário pelo id
		try {
			$usuario = $this->getUsuarioTable()->getUsuario($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('success');
		}

		// valores que não aparecem explicitamente no Form
		$senha = $usuario->senha;
		$permissao = $usuario->permissao;
		$dataCriacao = $usuario->dataCriacao;
		
		$form  = new UsuarioEditDadosForm();
		$form->get('submit')->setValue('Editar');
		$form->bind($usuario);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('success');
			}
			
			$form->setInputFilter($usuario->getInputFilter());
			$form->setData($request->getPost());
			
			if ($submit == 'Editar' && $form->isValid()) {
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$usuario->dataEdicao = $dataAtual;
				$usuario->dataCriacao = $dataCriacao;
				$usuario->senha = $senha;
				$usuario->permissao = $permissao;
				$this->getUsuarioTable()->saveUsuario($usuario);
				
				// atualiza o usuário editado na sessão
				$this->commonsPlugin()->writeStorage($usuario);
			}
			
			// Redirect to list of usuarios
			return $this->redirect()->toRoute('success');
		}

		return array(
			'id' => $id,
			'form' => $form,
		);
	}
	
	public function editpasswordAction()
	{
		// salva a permissão no layout
		$this->commonsPlugin()->setPermissaoLayout();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('success');
		}
		
		// recupera o usuário pelo id
		try {
			$usuario = $this->getUsuarioTable()->getUsuario($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('success');
		}
		
		$form  = new UsuarioPassForm();
		$form->get('submit')->setValue('Editar');
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('success');
			}
			
			$senhaantiga = $request->getPost('senha-antiga');
			$senhanova = $request->getPost('senha-nova');
			$senhanovachk = $request->getPost('senha-nova-chk');
			
			if ($senhaantiga == '') {
				$this->flashmessenger()->addMessage('Preencha o campo \'Senha Antiga\'.');
				return $this->redirect()->toRoute('usuario',array('action'=>'editpassword', 'id' => $id));
			} else if ($senhanova == '') {
				$this->flashmessenger()->addMessage('Preencha o campo \'Nova Senha\'.');
				return $this->redirect()->toRoute('usuario',array('action'=>'editpassword', 'id' => $id));
			} else if (md5($senhaantiga) != $usuario->senha) {
				$this->flashmessenger()->addMessage('A senha antiga digitada não corresponde à verdadeira senha.');
				return $this->redirect()->toRoute('usuario',array('action'=>'editpassword', 'id' => $id));
			} else if ($senhanova != $senhanovachk) {
				$this->flashmessenger()->addMessage('Os campos de \'Nova Senha\' estão diferentes!');
				return $this->redirect()->toRoute('usuario',array('action'=>'editpassword', 'id' => $id));
			}
			
			$form->setData($request->getPost());
			if ($submit == 'Editar' && $form->isValid()) {				
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$usuario->dataEdicao = $dataAtual;
				$usuario->senha = md5($senhanova);
				$this->getUsuarioTable()->saveUsuario($usuario);
				
				// atualiza o usuário editado na sessão
				$this->commonsPlugin()->writeStorage($usuario);
			}
			
			// Redirect to 'perfil'
			return $this->redirect()->toRoute('success');
		}

		return array(
			'id' => $id,
			'form' => $form,
			'messages' => $this->flashmessenger()->getMessages()
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
