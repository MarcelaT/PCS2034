<?php

namespace Acidente\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Acidente\Model\Acidente;
use Missao\Model\Missao;

use Acidente\Form\AcidenteForm;
use Acidente\Form\AcidenteFilterForm;
use Acidente\Form\AlocarMissaoForm;

class AcidenteController extends AbstractActionController
{
	protected $acidenteTable;
	protected $missaoTable;
	protected $tipoMissaoTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
		$permissao = $this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
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
			'permissao' => $permissao,
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

			if ($submit == 'Adicionar') {
				$acidente->localizacao = $request->getPost('localizacao');
				$acidente->descricao = $request->getPost('descricao');
				$acidente->bombeiro = $request->getPost('bombeiro');
				$acidente->policia = $request->getPost('policia');
				$acidente->numeroVitimas = $request->getPost('numeroVitimas');
				$acidente->obstrucao = $request->getPost('obstrucao');
				$acidente->status = 'cadastrado';
				
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$acidente->data = $dataAtual;
				
				$this->getAcidenteTable()->saveAcidente($acidente);
			}
			
			// Redirect to list of acidentes
			return $this->redirect()->toRoute('acidente');
		}
					
		return array(
			'form' => $form
		);
	}
	
	public function infoAction()
	{
		// verifica a permissão do usuário
		$permissao = $this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
		$idAcidente = (int) $this->params()->fromRoute('id', 0);
		if (!$idAcidente) {
			return $this->redirect()->toRoute('acidente');
		}
		
		$acidente = $this->getAcidenteTable()->getAcidente($idAcidente);
		$latLgn = $this->commonsPlugin()->getLatLng($acidente->localizacao);
		
		return array(
			'missoes' => $this->getMissaoTable()->getMissaoByIdAcidente($idAcidente),
			'idacidente' => $idAcidente,
			'permissao' => $permissao,
			'acidente' => $acidente,
			'lat' => $latLgn['lat'],
			'lng' => $latLgn['lng'],
		);
	}
	
	public function alocarmissaoAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('especialista');
		
		$idAcidente = (int) $this->params()->fromRoute('id', 0);
		if (!$idAcidente) {
			return $this->redirect()->toRoute('acidente');
		}
		
		return array(
			'missoes' => $this->getMissaoTable()->getMissaoByIdAcidente($idAcidente),
			'idacidente' => $idAcidente,
			'nomesTiposMissao' => $this->getTipoMissaoTable()->getArrayNomes(),
		);
	}
	
	public function salvarmissaoAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('especialista');
		
		$idAcidente = (int) $this->params()->fromRoute('id', 0);
		if (!$idAcidente) {
			return $this->redirect()->toRoute('acidente');
		}
		
		$form = new AlocarMissaoForm();
		$form->get('submit')->setValue('Alocar missão');
		
		// popula o select do tipo de missão
		$arrayTiposMissao = $this->getTipoMissaoTable()->getArrayNomes();
		$form->get('idTipoMissao')->setOptions(array(
			'value_options' => $arrayTiposMissao
		));
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('acidente', array(
					'action' => 'alocarmissao',
					'id' => $idAcidente,
				));
			}
			
			$Missao = new Missao();
			$form->setInputFilter($Missao->getInputFilter());
			$form->setData($request->getPost());
			
			if ($submit == 'Alocar missão') {
				$Missao->nome = $request->getPost('nome');
				$Missao->idTipoMissao = $request->getPost('idTipoMissao');
				$Missao->idAcidente = $idAcidente;
				$Missao->status = 'cadastrada';
				$Missao->recursosAlocados = false;
				
				date_default_timezone_set("Brazil/East");
				
				// gera um protocolo a partir do momento atual
				$Missao->protocolo = date('YmdHis').$idAcidente;
				
				$dataAtual = date('Y-m-d H:i:s');
				$Missao->dataCriacao = $dataAtual;
				
				$this->getMissaoTable()->saveMissao($Missao);
			}
			
			return $this->redirect()->toRoute('acidente', array(
				'action' => 'alocarmissao',
				'id' => $idAcidente,
			));
		}
		
		return array(
			'form' => $form,
			'idacidente' => $idAcidente,
		);
	}


	
	public function editAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');

		$idAcidente = (int) $this->params()->fromRoute('id', 0);
		if (!$idAcidente) {
			return $this->redirect()->toRoute('acidente', array('action' => 'add'));
		}
		
		// recupera o acidente pelo id
		try {
			$acidente = $this->getAcidenteTable()->getAcidente($idAcidente);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('acidente', array('action' => 'index'));
		}
		
		// armazena valores antigos que não são editados
		$acidenteData = $acidente->data;
		$acidenteStatus = $acidente->status;
		$acidenteStatusNome = $acidente->statusNome;
		
		$form = new AcidenteForm();
		$form->get('submit')->setValue('Editar');
		$form->bind($acidente);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('acidente');
			}
			
			$form->setInputFilter($acidente->getInputFilter());
			$form->setData($request->getPost());
			if ($submit == 'Editar') {
				$acidente->id = $request->getPost('id');
				$acidente->localizacao = $request->getPost('localizacao');
				$acidente->descricao = $request->getPost('descricao');
				$acidente->bombeiro = $request->getPost('bombeiro');
				$acidente->policia = $request->getPost('policia');
				$acidente->numeroVitimas = $request->getPost('numeroVitimas');
				$acidente->obstrucao = $request->getPost('obstrucao');
				$acidente->data = $acidenteData;
				$acidente->status = $acidenteStatus;
				$this->getAcidenteTable()->saveAcidente($acidente);
			}
			
			// Redirect to list of usuarios
			return $this->redirect()->toRoute('acidente');
		}

		return array(
			'id' => $idAcidente,
			'form' => $form,
			'acidenteData' => $acidenteData,
			'acidenteStatusNome' => $acidenteStatusNome,
		);
	}
	
	public function deleteAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
				
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('acidente');
		}
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');
			
			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getAcidenteTable()->deleteAcidente($id);
			}
			
			// Redirect to list of acidentes
			return $this->redirect()->toRoute('acidente');
		}
		
		return array(
			'id' => $id,
			'acidente' => $this->getAcidenteTable()->getAcidente($id),
		);
	}
	
	////////////
	// Tables //
	////////////
	public function getAcidenteTable()
	{
		 if (!$this->acidenteTable) {
			 $sm = $this->getServiceLocator();
			 $this->acidenteTable = $sm->get('Acidente\Model\AcidenteTable');
		 }
		 return $this->acidenteTable;
	}
	
	public function getMissaoTable()
	{
		if (!$this->missaoTable) {
			$sm = $this->getServiceLocator();
			$this->missaoTable = $sm->get('Missao\Model\MissaoTable');
		}
		return $this->missaoTable;
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
