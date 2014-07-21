<?php

namespace SanAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;

use SanAuth\Model\User;

class AuthController extends AbstractActionController
{
	protected $form;
	protected $storage;
	protected $authservice;
	protected $usuarioTable;
	
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
	
	public function getForm() {
		if (!$this->form) {
			$user	   = new User();
			$builder	= new AnnotationBuilder();
			$this->form = $builder->createForm($user);
		}
		
		return $this->form;
	}
	
	public function loginAction() {
		//if already login, redirect to success page 
		if ($this->getAuthService()->hasIdentity()) {
			return $this->redirect()->toRoute('success');
		}
		
		$form = $this->getForm();
		
		return array(
			'form'	  => $form,
			'messages'  => $this->flashmessenger()->getMessages()
		);
	}
	
	public function authenticateAction() {
		$form = $this->getForm();
		$redirect = 'login';
		
		$request = $this->getRequest();
		if ($request->isPost()){
		
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('home');
			}
			
			$login = $request->getPost('login');
			$senha = $request->getPost('senha');
			if ($login == '' && $senha == '') {
				$this->flashmessenger()->addMessage('Preencha os campos \'Login\' e \'Senha\'.');
				return $this->redirect()->toRoute('login');
			}
			
			$form->setData($request->getPost());
			if ($form->isValid()){		
				//check authentication...
				$this->getAuthService()->getAdapter()
									   ->setIdentity($login)
									   ->setCredential($senha);
									   
				$result = $this->getAuthService()->authenticate();
				foreach($result->getMessages() as $message)
				{
					//save message temporary into flashmessenger
					if ($message == 'A record with the supplied identity could not be found.') {
						$message = 'Usuário inexistente.';
					} else if ($message == 'Supplied credential is invalid.') {
						$message = 'Senha incorreta.';
					}
					$this->flashmessenger()->addMessage($message);
				}
				
				if ($result->isValid()) {
					$redirect = 'success';
					//check if it has rememberMe :
					if ($request->getPost('lembrarme') == 1 ) {
						$this->getSessionStorage()->setRememberMe(1);
						//set storage again
						$this->getAuthService()->setStorage($this->getSessionStorage());
					}
					$this->getAuthService()->setStorage($this->getSessionStorage());
					
					// recupera o usuário a partir do login
					try {
						$usuario = $this->getUsuarioTable()->getUsuarioByLogin($login);
					} catch (\Exception $ex) {
						return $this->redirect()->toRoute('login', array('action' => 'login'));
					}
					
					// salva o usuário na sessão, para poder recuperar o nome e a permissão
					$this->getAuthService()->getStorage()->write($usuario);
					
				}
			}
		}
		
		return $this->redirect()->toRoute($redirect);
	}
	
	public function logoutAction() {
		if ($this->getAuthService()->hasIdentity()) {
			$this->getSessionStorage()->forgetMe();
			$this->getAuthService()->clearIdentity();
			$this->flashmessenger()->addMessage("Você se desconectou.");
		}
		
		return $this->redirect()->toRoute('login');
	}
	
	public function getUsuarioTable() {
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Usuario\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}
}