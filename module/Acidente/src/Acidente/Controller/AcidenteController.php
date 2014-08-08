<?php

namespace Acidente\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Acidente\Model\Acidente;
use Missao\Model\Missao;

use Acidente\Form\AcidenteForm;
use Acidente\Form\AcidenteFilterForm;

class AcidenteController extends AbstractActionController
{
	protected $acidenteTable;
	
    public function indexAction()
    {
        //$missoes = $this->getMissaoTable()->getMissaoByIdAcidente($idAcidente)
        // salva a permissão no layout
        $this->commonsPlugin()->setPermissaoLayout();
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
        
		$form = new AcidenteFilterForm();
		$form->get('submit')->setValue('Filtrar');
		
		$acidentes = $this->getAcidenteTable()->fetchAll();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega os campos do filtro
				$localizacao = $request->getPost('localizacao');
				$descricao = $request->getPost('descricao');
				$dataDe = $request->getPost('dataDe');
				$dataAte = $request->getPost('dataAte');
				$bombeiro = $request->getPost('bombeiro');
				$policia = $request->getPost('policia');
				$numeroVitimas = $request->getPost('numeroVitimas');
				$obstrucao = $request->getPost('obstrucao');
				
				// preenche a lista filtrada de acidentes
				$acidentes = $this->getAcidenteTable()->getAcidentesFiltered($localizacao, $descricao,
						$dataDe, $dataAte, $bombeiro, $policia, $numeroVitimas, $obstrucao);
			}
		}
		
		return new ViewModel(array(

			'form' => $form,
			'acidentes' => $acidentes,
         ));
    }

    public function addAction()
    {
        // verifica a permissão do usuário
        $this->commonsPlugin()->verificaPermissao('coordenador');
		
		$form = new AcidenteForm();
        $form->get('submit')->setValue('Adicionar');
		
        $request = $this->getRequest();
        if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
            $submit = $request->getPost('submit');
            if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('acidente');
            }

            $acidente = new Acidente();
            $form->setInputFilter($acidente->getInputFilter());
            $form->setData($request->getPost());
            //$acidente->status = "cadastrado";

            if ($submit == 'Adicionar' && $form->isValid()) {
                $acidente->exchangeArray($form->getData());
                date_default_timezone_set("Brazil/East");
                $dataAtual = date('Y-m-d H:i:s');
                $acidente->data = $dataAtual;
                $this->getAcidenteTable()->saveAcidente($acidente);
            }
			
            // Redirect to list of acidentes
            return $this->redirect()->toRoute('acidente');
        }
        return array('form' => $form);
    }
	
	public function infoAction()
    {
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
		
        $idAcidente = (int) $this->params()->fromRoute('id', 0);

         return new ViewModel(array(
            'missoes' => $this->getMissaoTable()->getMissaoByIdAcidente($idAcidente),
            'idacidente' => $idAcidente,
            'permissao' => $this->commonsPlugin()->getPermissaoUsuario(),
            'acidente' => $this->getAcidenteTable()->getAcidente($idAcidente),

        ));    
     }
	
	public function getAcidenteTable()
    {
         if (!$this->acidenteTable) {
             $sm = $this->getServiceLocator();
             $this->acidenteTable = $sm->get('Acidente\Model\AcidenteTable');
         }
         return $this->acidenteTable;
    }

    public function alocarmissaoAction(){
        $this->commonsPlugin()->verificaPermissao('especialista');

        $idAcidente = (int) $this->params()->fromRoute('id', 0);

         return new ViewModel(array(
            'missoes' => $this->getMissaoTable()->getMissaoByIdAcidente($idAcidente),
            'idacidente' => $idAcidente,

        ));


    }

    public function salvarmissaoAction(){

        $this->commonsPlugin()->verificaPermissao('especialista');

        $idAcidente = (int) $this->params()->fromRoute('id', 0);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $idAcidente = $request->getPost('idAcidente');

            $Missao = new Missao();
            $Missao->nome = $request->getPost('nome');
            $Missao->idTipoMissao = $request->getPost('tipodemissao');
            $Missao->status = "cadastrada";
            $Missao->idAcidente = $idAcidente;

            $now = date('YmdHis');

            $Missao->protocolo = $now;
            $Missao->recursosAlocados = false;

            $this->getMissaoTable()->saveMissao($Missao);
            return $this->redirect()->toRoute('acidente', array('action' => 'alocarmissao', 'id' => $idAcidente));

        }

        
        return new ViewModel(array(
            'tipoMissoes' => $this->getTipoMissaoTable()->fetchAll(),
            'idacidente' => $idAcidente,
        ));


    }


	
    public function getMissaoTable()
    {
       // if (!$this->missaoTable) {
            $sm = $this->getServiceLocator();
            $this->missaoTable = $sm->get('Missao\Model\MissaoTable');
       // }
        return $this->missaoTable;
    }

    public function getTipoMissaoTable()
    {
       // if (!$this->tipoMissaoTable) {
            $sm = $this->getServiceLocator();
            $this->tipoMissaoTable = $sm->get('TipoMissao\Model\TipoMissaoTable');
        //}
        return $this->tipoMissaoTable;
    }

}
