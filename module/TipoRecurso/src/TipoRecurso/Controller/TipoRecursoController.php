<?php

namespace TipoRecurso\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use TipoRecurso\Model\TipoRecurso;
use TipoRecurso\Form\TipoRecursoForm;

class TipoRecursoController extends AbstractActionController
{
    protected $tipoRecursoTable;
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
        
        return new ViewModel(array('tipoRecursos' => $this->getTipoRecursoTable()->fetchAll()));
    }

    public function addAction()
    {
        $form = new TipoRecursoForm();
        $form->get('submit')->setValue('Adicionar');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $tipoRecurso = new TipoRecurso();
            $form->setInputFilter($tipoRecurso->getInputFilter());
            $form->setData($request->getPost());
            
            $submit = $request->getPost('submit');
            if ($submit == 'Adicionar' && $form->isValid()) {
                $tipoRecurso->exchangeArray($form->getData());
                $this->getTipoRecursoTable()->saveTipoRecurso($tipoRecurso);
            }
            
            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tiporecurso');
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tiporecurso', array('action' => 'add'));
        }

        // Get the Usuario with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('tiporecurso', array('action' => 'index'));
        }

        $form  = new TipoRecursoForm();
        $form->bind($tipoRecurso);
        $form->get('submit')->setAttribute('value', 'Editar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($tipoRecurso->getInputFilter());
            $form->setData($request->getPost());

            $submit = $request->getPost('submit');
            if ($submit == 'Editar' && $form->isValid()) {
                //$tipoRecurso->id = 1;

                $this->getTipoRecursoTable()->saveTipoRecurso($tipoRecurso);
            }
            
            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tiporecurso');
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
            return $this->redirect()->toRoute('tiporecurso');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del');

            if ($del == 'Sim') {
                $id = (int) $request->getPost('id');
                $this->getTipoRecursoTable()->deleteTipoRecurso($id);
            }

            // Redirect to list of usuarios
            return $this->redirect()->toRoute('tiporecurso');
        }

        return array(
            'id' => $id,
            'tipoRecurso' => $this->getTipoRecursoTable()->getTipoRecurso($id)
        );
    }
    
    public function getTipoRecursoTable()
    {
        if (!$this->tipoRecursoTable) {
            $sm = $this->getServiceLocator();
            $this->tipoRecursoTable = $sm->get('TipoRecurso\Model\TipoRecursoTable');
        }
        return $this->tipoRecursoTable;
    }
}
