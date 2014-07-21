<?php

namespace SanAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractActionController
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
			$this->storage = $this->getAuthService()->getStorage();
		}
		return $this->storage;
	}
	
	public function indexAction()
	{
		if (!$this->getAuthService()->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		
		return new ViewModel(
			array(
				'usuario' => $this->getAuthService()->getStorage()->read('usuario'),
			)
		);
	}
}