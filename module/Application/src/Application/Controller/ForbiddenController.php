<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ForbiddenController extends AbstractActionController
{
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
		
		// salva a permissão no layout
		$this->layout()->setVariable('permissao', $permissao);
		
        return new ViewModel();
    }
}
