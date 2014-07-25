<?php

namespace SanAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractActionController
{
	
	public function indexAction()
	{
		// salva a permissão no layout
		$this->commonsPlugin()->setPermissaoLayout();
		
		// não permite que essa página seja acessada sem autenticacao
		if (!$this->commonsPlugin()->isAutenticado()){
			return $this->redirect()->toRoute('login');
		}
		
		// recupera o nome do usuário
		$usuario_nome = $this->commonsPlugin()->readStorage('usuario')->nome;
		
		// retorna para a view com o nome do usuário
		return new ViewModel(array('nome_usuario' => $usuario_nome));
	}
}