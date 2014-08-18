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
	
	public function getPermissaoUsuario() {
		$permissao = '';
		
		// recupera o usuário
		$usuario = $this->getAuthService()->getStorage()->read('usuario');
		
		// verifica se existe um usuário para adicionar a permissao
		if ($usuario) {
			$permissao = $usuario->permissao;
		}
		
		return $permissao;
	}
	
	// seta a permissão do usuário no layout para ser recuperado e gerar os menus
	public function setPermissaoLayout() {
		$permissao = $this->getPermissaoUsuario();
		$this->getController()->layout()->setVariable('permissao', $permissao);
		return $permissao;
	}
	
	// verifica se o usuário pode ou não acessar a página
	// par aúnica permissão
	public function verificaPermissao($permUsuario) {
		if (!$this->isAutenticado()){
			return $this->getController()->redirect()->toRoute('login');
		}
		
		$permissao = $this->setPermissaoLayout();
		
		// apenas usuários credenciados podem ter acesso!
		// administradores tem TODAS as funções do sistema!
		if ($permissao != 'administrador' && $permissao != $permUsuario){
			return $this->getController()->redirect()->toRoute('forbidden');
		}
		
		return $permissao;
	}
	
	// verifica se o usuário pode ou não acessar a página
	// para múltimas permissões
	public function verificaPermissoes($permUsuariosArray) {
		if (!$this->isAutenticado()){
			return $this->getController()->redirect()->toRoute('login');
		}
		
		$permissao = $this->setPermissaoLayout();
		
		$validou = false;
		foreach ($permUsuariosArray as $permUsuario){
			if ($permissao == 'administrador' || $permissao == $permUsuario){
				$validou = true;
				break;
			}
		}
		
		if (!$validou) {
			return $this->getController()->redirect()->toRoute('forbidden');
		}
		
		return $permissao;
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

	public function calculaPorcentagem($numero, $total) {
		if ($total == 0) return '0.00';
		return number_format((($numero/$total) * 100), 2);
	}
	
	public function calculaMedia($numero, $total) {
		if ($total == 0) return '0.00';
		return number_format($numero/$total, 2);
	}
	
	// traz a lagitude/longitude do endereço via google maps
	public function getLatLng($address) {
		try {
			$address = urlencode($address);
			$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

			$output = json_decode($geocode);
			
			if (null != $output->results) {
				$lat = $output->results[0]->geometry->location->lat;
				$lng = $output->results[0]->geometry->location->lng;
				if(is_numeric($lat) && is_numeric($lng)){
					return array("lat" => $lat, "lng" => $lng);
				}
			}
		} catch(Exception $e) {	
			return false;
		}
	}
	
}