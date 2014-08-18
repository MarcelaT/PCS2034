<?php
namespace Missao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Missao\Model\Missao;
use Missao\Form\MissaoForm;
use Missao\Form\MissaoFilterForm;
use Missao\Form\MissaoStatusForm;
use Missao\Form\NovoRecursoForm;

use Missao\Model\Recurso;
use Missao\Model\RecursoNome;
use Missao\Model\EditarRecurso;

use Acidente\Model\Acidente;

class MissaoController extends AbstractActionController
{
	protected $acidenteTable;
	protected $missaoTable;
	protected $tipoMissaoTable;
	protected $recursoTable;
	protected $tipoRecursoTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
		$form = new MissaoFilterForm();
		$form->get('submit')->setValue('Filtrar');
		
		try {
			$Missoes = $this->getMissaoTable()->fetchAll();
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}
		
		// popula o select do tipo de missão
		$arrayTiposMissao = $this->getTipoMissaoTable()->getArrayNomes();
		array_unshift($arrayTiposMissao, 'Qualquer');
		$form->get('idTipoMissao')->setOptions(array(
			'value_options' => $arrayTiposMissao
		));
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega os campos do filtro
				$idTipoMissao = $request->getPost('idTipoMissao');
				$nome = $request->getPost('nome');
				$protocolo = $request->getPost('protocolo');
				$status = $request->getPost('status');
				
				// preenche a lista filtrada de acidentes
				$Missoes = $this->getMissaoTable()->getMissoesFiltered($idTipoMissao, $nome,
						$protocolo, $status);
			}
		}
		
		return new ViewModel(array(
			'form' => $form,
			'Missoes' => $Missoes,
			'permissao' => $this->commonsPlugin()->getPermissaoUsuario(),
		));
	}

	public function alocarrecursosAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		if (!$idMissao) {
			return $this->redirect()->toRoute('missao');
		}
		
		$tiposRecurso = $this->getTipoRecursoTable()->fetchAll();
		$total = count($tiposRecurso);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$submit = $request->getPost('submit');
			if ($submit == 'Alocar recursos') {
				$tiposRecursoNomes = $this->getTipoRecursoTable()->getArrayNomes();
				$existeRecurso = false;
				
				// persiste os recursos com qtd > 0
				foreach ($tiposRecursoNomes as $idTipoRecurso => $nome) {
					$Recurso = new Recurso();
					$quantidade = 'qtd'.$idTipoRecurso;
					$Recurso->quantidade = (int) $request->getPost($quantidade);
					$Recurso->idTipoRecurso = $idTipoRecurso;
					$Recurso->idMissao = $idMissao;
					
					if ($Recurso->quantidade != 0) {
						$existeRecurso = true;
						date_default_timezone_set("Brazil/East");
						$dataAtual = date('Y-m-d H:i:s');
						$Recurso->dataCriacao = $dataAtual;
						$this->getRecursoTable()->saveRecurso($Recurso);
					}
				}
				
				// atualiza a missão (apenas se houve algum recurso persistido)
				if ($existeRecurso) {
					try {
						$Missao = $this->getMissaoTable()->getMissao($idMissao);
					} catch (\Exception $ex) {
						return $this->redirect()->toRoute('missao', array('action' => 'index'));
					}
					
					$Missao->status = "em_andamento";
					$Missao->recursosAlocados = true;
					$this->getMissaoTable()->saveMissao($Missao);
				}
			}
			
			// Retorna para a lista de missões
			return $this->redirect()->toRoute('acidente', array(
				'action' => 'info',
				'id' => $Missao->idAcidente,
			));
		}
		
		return array(
			'tiposRecurso' => $tiposRecurso,
			'idMissao'=> $idMissao,
			'total'=> $total
		);
	}
	
	public function detalhesAction() {
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissoes(array('especialista', 'coordenador'));
		
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		
		try {
			$Missao = $this->getMissaoTable()->getMissao($idMissao);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}

		try {
			$TipoMissao = $this->getTipoMissaoTable()->getTipoMissao($Missao->idTipoMissao);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}

		$Recursos = $this->getRecursoTable()->getRecursosidMissao($idMissao);
		$recursosLista = array();
		foreach($Recursos as $recurso){
			$tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($recurso->idTipoRecurso);
			$RecursoNome = new RecursoNome();
			$RecursoNome->quantidade = $recurso->quantidade;
			$RecursoNome->nome = $tipoRecurso->nome;
			array_push($recursosLista, $RecursoNome);
		}

		return array(
			'recursosLista' => $recursosLista,
			'Missao' => $Missao,
			'TipoMissao' => $TipoMissao,
		);
	}
	
	public function editAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('especialista');
		
		// para redirecionar o usuário para acidente ou missão, depende de onde ele veio
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			// retorna para a última página visitada pelo usuário (via acidentes ou missão)
			return $this->redirect()->toUrl($request->getPost('redirect-url'));
		}
		
		// recupera a missao pelo id
		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			// retorna para a última página visitada pelo usuário (via acidentes ou missão)
			return $this->redirect()->toUrl($request->getPost('redirect-url'));
		}
		
		// armazena valores antigos que não são editados
		$missaoData = $missao->dataCriacao;
		$missaoProtocolo = $missao->protocolo;
		$missaoStatus = $missao->status;
		$missaoStatusNome = $missao->statusNome;
		
		$form = new MissaoForm();
		$form->get('submit')->setValue('Editar');
		
		// popula o select do tipo de missão
		$arrayTiposMissao = $this->getTipoMissaoTable()->getArrayNomes();
		$form->get('idTipoMissao')->setOptions(array(
			'value_options' => $arrayTiposMissao
		));
		
		$form->bind($missao);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				// retorna para a última página visitada pelo usuário (via acidentes ou missão)
				return $this->redirect()->toUrl($request->getPost('redirect-url'));
			}
			
			$form->setInputFilter($missao->getInputFilter());
			$form->setData($request->getPost());
			if ($submit == 'Editar') {
				$missao->id = $request->getPost('id');
				$missao->nome = $request->getPost('nome');
				$missao->idTipoMissao = $request->getPost('idTipoMissao');
				$missao->dataCriacao = $missaoData;
				$missao->status = $missaoStatus;
				$missao->protocolo = $missaoProtocolo;
				$this->getMissaoTable()->saveMissao($missao);
			}
			
			// retorna para a última página visitada pelo usuário (via acidentes ou missão)
			return $this->redirect()->toUrl($request->getPost('redirect-url'));
		}

		return array(
			'id' => $id,
			'form' => $form,
			'missaoData' => $missaoData,
			'missaoProtocolo' => $missaoProtocolo,
			'missaoStatusNome' => $missaoStatusNome,
			'redirectUrl' => $redirectUrl,
		);
	}
	
	public function deleteAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao');
		}

		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}
		
		try {
			$tipoMissao = $this->getTipoMissaoTable()->getTipoMissao($missao->idTipoMissao);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action' => 'index'));
		}
		
		// para redirecionar o usuário para acidente ou missão, depende de onde ele veio
		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$this->getMissaoTable()->deleteMissao($id);
			}
			
			// retorna para a última página visitada pelo usuário (via acidentes ou missão)
			return $this->redirect()->toUrl($request->getPost('redirect-url'));
		}
		
		return array(
			'id' => $id,
			'missao' => $this->getMissaoTable()->getMissao($id),
			'tipoMissao' => $tipoMissao,
			'redirectUrl' => $redirectUrl,
		);
	}
	
	public function missaoprotocoloAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$form = new MissaoStatusForm();
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			// verifica se o usuário clicou em 'cancelar'
			$submit = $request->getPost('submit');
			if ($submit == 'Cancelar') {
				return $this->redirect()->toRoute('success');
			}
			
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$protocolo = $request->getPost('protocolo');
				
				if ($protocolo == '') {
					$this->flashmessenger()->addMessage('Preencha o campo \'Protocolo\'.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				// recupera missao pelo protocolo
				try {
					$missao = $this->getMissaoTable()->getMissaoByProtocolo($protocolo);
				} catch (\Exception $ex) {
					$this->flashmessenger()->addMessage('\'Protocolo\' inválido ou inexistente.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				// verifica se a missão está em estado transitório
				if ($missao->status != 'em_andamento') {
					$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
					return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
				}
				
				// Redirect para a página de seleção do status
				return $this->redirect()->toRoute('missao', array(
					'action' => 'atualizarstatus',
					'id' => $missao->id,
				));
			}
		}

		return array(
			'form' => $form,
			'messages' => $this->flashmessenger()->getMessages(),
		);
	}
	
	public function atualizarstatusAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// recupera missao pelo id
		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// verifica se a missão está em estado transitório
		if ($missao->status != 'em_andamento') {
			$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		$tipoMissao = $this->getTipoMissaoTable()->getTipoMissao($missao->idTipoMissao);
		
		return array(
			'id' => $id,
			'missao' => $missao,
			'tipoMissao' => $tipoMissao->nome,
		);
	}
	
	public function updateStatus($id, $status)
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		// recupera a missao pelo id
		try {
			$missao = $this->getMissaoTable()->getMissao($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// verifica se a missão está em estado transitório
		if ($missao->status != 'em_andamento') {
			$this->flashmessenger()->addMessage('Esta missão não possui status \'Em andamento\'.');
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		// muda o status
		try {
			$this->getMissaoTable()->atualizarStatusMissao($id, $status);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}

		// verifica se todas as missões de determinado acidente foram finalizadas
		$acidenteFinalizado = true;
		$missoes = $this->getMissaoTable()->getMissaoByIdAcidente($missao->idAcidente);
		foreach ($missoes as $missao) {
			if ($missao->status == 'em_andamento') {
				$acidenteFinalizado = false;
				break;
			}
		}
		// se não há mais missões em andamento para o acidente, ele finaliza
		if ($acidenteFinalizado) {
			$this->getAcidenteTable()->atualizarStatusAcidente($missao->idAcidente, "finalizado");
		}
		
		return array(
			'id' => $id,
		);
	}
	
	public function statusconcluidaAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'concluida');
	}
	
	public function statusabortadaAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('lider_missao');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('missao', array('action'=>'missaoprotocolo'));
		}
		
		return $this->updateStatus($id, 'abortada');
	}

	public function editarrecursosAction(){

		$idMissao = (int) $this->params()->fromRoute('id', 0);
		if (!$idMissao) {
			return $this->redirect()->toRoute('missao');
		}

		$Recursos = $this->getRecursoTable()->getRecursosidMissao($idMissao);



		
			$editarRecursoLista = array();


			foreach($Recursos as $recurso){

			$tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($recurso->idTipoRecurso);

			$editarRecurso = new EditarRecurso();

				$editarRecurso->idRecurso = $recurso->id;
				$editarRecurso->nome = $tipoRecurso->nome;
				$editarRecurso->quantidade = $recurso->quantidade;
				$editarRecurso->idTipoRecurso = $tipoRecurso->id;

			array_push($editarRecursoLista, $editarRecurso);

			}

		return array(
			'editarRecursoLista' => $editarRecursoLista,
			'idMissao' => $idMissao,
		);		
	}


	public function editarrecurso1Action(){

		$this->commonsPlugin()->verificaPermissao('coordenador');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$recurso= new Recurso();
			$recurso->id = $request->getPost('idRecurso');
			$recurso->quantidade = $request->getPost('quantidade');	
			$recurso->idMissao = $request->getPost('idMissao');	
			$recurso->idTipoRecurso = $request->getPost('idTipoRecurso');	
			$this->getRecursoTable()->saveRecurso($recurso);
			return $this->redirect()->toRoute('missao', array('action' => 'editarrecursos', 'id' => $recurso->idMissao));

		}

		$id = (int) $this->params()->fromRoute('id', 0);
		$recurso = $this->getRecursoTable()->getRecurso($id);
		$tipoRecurso = $this->getTipoRecursoTable()->getTipoRecurso($recurso->idTipoRecurso);
		$editarRecurso = new EditarRecurso();

		$editarRecurso->idRecurso = $recurso->id;
		$editarRecurso->nome = $tipoRecurso->nome;
		$editarRecurso->quantidade = $recurso->quantidade;
		$editarRecurso->idTipoRecurso = $tipoRecurso->id;
		$editarRecurso->idMissao = $recurso->idMissao;


		return array(
			'editarRecurso' => $editarRecurso,
		);

	}

		public function novoRecursoAction(){

		$this->commonsPlugin()->verificaPermissao('coordenador');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$Recurso = new Recurso();
			$Recurso->quantidade = (int) $request->getPost('quantidade');
			$Recurso->idTipoRecurso = $request->getPost('idTipoRecurso');	
			$Recurso->idMissao =  $request->getPost('idMissao');
			
			if ($Recurso->quantidade != 0) {
				$existeRecurso = true;
				date_default_timezone_set("Brazil/East");
				$dataAtual = date('Y-m-d H:i:s');
				$Recurso->dataCriacao = $dataAtual;
				$this->getRecursoTable()->saveRecurso($Recurso);
			}
			return $this->redirect()->toRoute('missao', array('action' => 'editarrecursos', 'id' => $Recurso->idMissao));
	
		}	
		
		$idMissao = (int) $this->params()->fromRoute('id', 0);
		
		$form = new novoRecursoForm();
		$form->get('submit')->setValue('Alocar Recurso');
		$form->get('idMissao')->setValue($idMissao);

		$arrayTiposMissao = $this->getTipoRecursoTable()->getArrayNomes();
		$form->get('idTipoRecurso')->setOptions(array(
			'value_options' => $arrayTiposMissao
		));

		return array(
			'form' => $form,
			'idMissao' => $idMissao,
		);
	}

	public function removerrecursoAction(){
		$this->commonsPlugin()->verificaPermissao('coordenador');

		$id = (int) $this->params()->fromRoute('id', 0);
		$recurso = $this->getRecursoTable()->getRecurso($id);

		if($id!=0){
			$this->getRecursoTable()->deleteRecurso($id);

		}
		return $this->redirect()->toRoute('missao', array('action' => 'editarrecursos', 'id' => $recurso->idMissao));


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
	
	public function getRecursoTable()
	{
		if (!$this->recursoTable) {
			$sm = $this->getServiceLocator();
			$this->recursoTable = $sm->get('Missao\Model\RecursoTable');
		}
		return $this->recursoTable;
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
