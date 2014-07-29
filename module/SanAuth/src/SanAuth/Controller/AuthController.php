<?php

namespace SanAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;

use SanAuth\Model\User;

class AuthController extends AbstractActionController
{
	protected $form;
	protected $usuarioTable;

	public function getForm() {
		if (!$this->form) {
			$user = new User();
			$builder = new AnnotationBuilder();
			$this->form = $builder->createForm($user);
		}
		
		return $this->form;
	}
	
	public function loginAction() {
		// salva a permissão no layout
		$this->commonsPlugin()->setPermissaoLayout();
		
		// se já foi autenticado, redireciona para a página de sucesso
		if ($this->commonsPlugin()->isAutenticado()) {
			return $this->redirect()->toRoute('success');
		}
		
		$form = $this->getForm();
		
		return array(
			'form' => $form,
			'messages' => $this->flashmessenger()->getMessages()
		);
	}
	
	public function authenticateAction() {
		// salva a permissão no layout
		$this->commonsPlugin()->setPermissaoLayout();
		
		$form = $this->getForm();
		$redirect = 'login';
		
		$request = $this->getRequest();
		if ($request->isPost()){
		
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('home');
			}
			
			// faz validações básicas
			$login = $request->getPost('login');
			$senha = $request->getPost('senha');
			if ($login == '' && $senha == '') {
				$this->flashmessenger()->addMessage('Preencha os campos \'Login\' e \'Senha\'.');
				return $this->redirect()->toRoute('login');
			} else if ($login == '') {
				$this->flashmessenger()->addMessage('Preencha o campo \'Login\'.');
				return $this->redirect()->toRoute('login');
			} else if ($senha == '') {
				$this->flashmessenger()->addMessage('Preencha o campo \'Senha\'.');
				return $this->redirect()->toRoute('login');
			}
			
			$form->setData($request->getPost());
			if ($form->isValid()){		
				//check authentication...
				$this->commonsPlugin()->getAuthService()->getAdapter()
									   ->setIdentity($login)
									   ->setCredential($senha);
									   
				$result = $this->commonsPlugin()->getAuthService()->authenticate();
				foreach($result->getMessages() as $message)
				{
					// traduz as mensagens que vem em inglês
					if ($message == 'A record with the supplied identity could not be found.') {
						$message = 'Usuário inexistente.';
					} else if ($message == 'Supplied credential is invalid.') {
						$message = 'Senha incorreta.';
					} else if ($message == 'Authentication successful.') {
						continue;
					}
					$this->flashmessenger()->addMessage($message);
				}
				
				if ($result->isValid()) {
					$redirect = 'success';
					//check if it has rememberMe :
					if ($request->getPost('lembrarme') == 1 ) {
						$this->commonsPlugin()->getSessionStorage()->setRememberMe(1);
					}
					//set storage
					$this->commonsPlugin()->setStorage();
					
					// recupera o usuário a partir do login
					try {
						$usuario = $this->getUsuarioTable()->getUsuarioByLogin($login);
					} catch (\Exception $ex) {
						return $this->redirect()->toRoute('login', array('action' => 'login'));
					}
					
					// salva o usuário na sessão, para poder recuperar o nome e a permissão
					$this->commonsPlugin()->writeStorage($usuario);
				}
			}
		}
		
		return $this->redirect()->toRoute($redirect);
	}
	
	public function logoutAction() {
		if ($this->commonsPlugin()->isAutenticado()) {
			$this->commonsPlugin()->logout();
			$this->flashmessenger()->addMessage("Você se desconectou.");
		}

		$this->commonsPlugin()->setPermissaoLayout();
		
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