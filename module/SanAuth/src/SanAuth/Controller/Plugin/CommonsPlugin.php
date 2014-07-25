<?php

namespace SanAuth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use SanAuth\Model\User;

// essa classe contém alguns métodos comuns, visíveis a todos os Controllers do sistema
class CommonsPlugin extends AbstractPlugin
{
	protected $storage;
	protected $authservice;
	
	public function getAuthService() {
		if (!$this->authservice) {
			$this->authservice = $this->getController()->getServiceLocator()->get('AuthService');
		}
		return $this->authservice;
	}
	
	public function getSessionStorage() {
		if (!$this->storage) {
			$this->storage = $this->getController()->getServiceLocator()->get('SanAuth\Model\MyAuthStorage');
		}
		return $this->storage;
	}
	
	// seta a permissão do usuário no layout para ser recuperado e gerar os menus
	public function setPermissaoLayout() {
		$permissao = '';
		
		// recupera o usuário
		$usuario = $this->getAuthService()->getStorage()->read('usuario');
		
		// verifica se existe um usuário para adicionar a permissao
		if ($usuario) {
			$permissao = $usuario->permissao;
		}
		
		$this->getController()->layout()->setVariable('permissao', $permissao);
		
		return $permissao;
	}
	
	// verifica se o usuário pode ou não acessar a página
	public function verificaPermissao($permUsuario) {
		if (!$this->isAutenticado()){
			return $this->getController()->redirect()->toRoute('login');
		}
		
		$permissao = $this->setPermissaoLayout();
		
		// apenas administradores podem ter acesso!
		if ($permissao != $permUsuario){
			return $this->getController()->redirect()->toRoute('forbidden');
		}
	}
	
	public function isAutenticado() {
		return $this->getAuthService()->hasIdentity();
	}
	
	// realiza o logout
	public function logout() {
		$this->getSessionStorage()->forgetMe();
		$this->getAuthService()->clearIdentity();
	}
	
	// armazena o storage da sessão na autenticação
	public function setStorage() {
		$this->getAuthService()->setStorage($this->getSessionStorage());
	}
	
	// lê um dado guardado no storage
	public function readStorage($data) {
		return $this->getAuthService()->getStorage()->read($data);
	}
	
	// escreve um dado no storage
	public function writeStorage($data) {
		return $this->getAuthService()->getStorage()->write($data);
	}

}