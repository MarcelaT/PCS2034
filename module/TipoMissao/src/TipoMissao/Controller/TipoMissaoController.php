<?php

namespace TipoMissao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use TipoMissao\Model\TipoMissao;
use TipoMissao\Form\TipoMissaoForm;

class TipoMissaoController extends AbstractActionController
{
    protected $tipoMissaoTable;
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
        
        // apenas administradores podem ter acesso!
        $permissao = $this->getAuthService()->getStorage()->read('usuario')->permissao;
        if ($permissao != 'administrador'){
            return $this->redirect()->toRoute('login');
        }
        
        return new ViewModel(array('tipoMissoes' => $this->getTipoMissaoTable()->fetchAll()));
    }

    public function addAction()
    {
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
                $this->getTipoMissaoTable()->saveTipoMissao($tipoMissao);
            }
            
            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tipomissao');
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tipomissao', array('action' => 'add'));
        }

        // Get the Usuario with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
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
                //$tipoMissao->id = 1;

                $this->getTipoMissaoTable()->saveTipoMissao($tipoMissao);
            }
            
            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tipomissao');
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
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

            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tipomissao');
        }

        return array(
            'id' => $id,
            'tipoMissao' => $this->getTipoMissaoTable()->getTipoMissao($id)
        );
    }
    
    public function getTipoMissaoTable()
    {
        if (!$this->tipoMissaoTable) {
            $sm = $this->getServiceLocator();
            $this->tipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
        }
        return $this->tipoMissaoTable;
    }
}
