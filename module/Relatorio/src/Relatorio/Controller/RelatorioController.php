<?php
namespace Relatorio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Relatorio\Form\RelatorioPeriodoForm;

class RelatorioController extends AbstractActionController
{
	protected $acidenteTable;
	protected $missaoTable;
	protected $tipoMissaoTable;
	protected $recursoTable;
	protected $tipoRecursoTable;
	protected $usuarioTable;
	
	public function indexAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		return new ViewModel(array(
			'permissao' => $this->commonsPlugin()->getPermissaoUsuario(),
		));
	}
	
	public function acidentesAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		$form = new RelatorioPeriodoForm();
		$form->get('submit')->setValue('Filtrar');
		
		$dataDe = 'Início';
		$dataAte = 'Agora';
		
		$dados = $this->gerarEstatisticasAcidentes($dataDe, $dataAte);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// pega as datas do período
				$postDataDe = $request->getPost('dataDe');
				if (null != $postDataDe && $postDataDe != '' && $postDataDe != 0) {
					$dataDe = $postDataDe;
				}
				
				$postDataAte = $request->getPost('dataAte');
				if (null != $postDataAte && $postDataAte != '' && $postDataAte != 0) {
					date_default_timezone_set("Brazil/East");
					$dataAtual = date('d-m-Y');
					// verifica se o usuário não pegou uma dataAte maior que a atual
					$dateDiff = date_diff(date_create($postDataAte), date_create($dataAtual));
					if ($dateDiff->invert == 0 && $dateDiff->d > 0) {
						$dataAte = $postDataAte;
					}
				}
				
				// só recalcula valores caso haja modificação do período
				if ($dataDe != 'Início' || $dataAte != 'Agora') {
					$dados = $this->gerarEstatisticasAcidentes($dataDe, $dataAte);
				}
			}
		}
		
		return array(
			'form' => $form,
			'dataDe' => $dataDe,
			'dataAte' => $dataAte,
			'dados' => $dados,
		);
	}
	
	private function gerarEstatisticasAcidentes($dataDe, $dataAte) {
		// recuperando todos os acidentes
		$acidentes = $this->getAcidenteTable()->getAcidentesCadastrados($dataDe, $dataAte);
		
		// gerando estatísticas
		$total = count($acidentes);
		$bombeiros = 0;
		$policia = 0;
		$vitimas = 0;
		$obstrucoes = 0;
		
		foreach($acidentes as $acidente) {
			$bombeiros += $acidente->bombeiro;
			$policia += $acidente->policia;
			$vitimas += $acidente->numeroVitimas;
			$obstrucoes += $acidente->obstrucao;
		}
		
		$dados['Total de acidentes'] = $total;
		$dados['Presença de bombeiros no local'] = (($bombeiros/$total) * 100).' %';
		$dados['Presença de polícia no local'] = (($policia/$total) * 100).' %';
		$dados['Número total de vítimas'] = $vitimas;
		$dados['Número médio de vítimas'] = $vitimas/$total;
		$dados['Quantidade total de obstruções nas vias'] = $obstrucoes;
		$dados['Quantidade média de obstruções nas vias'] = $obstrucoes/$total;
		
		return $dados;
	}
	
	public function missoesAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('coordenador');
		
		return array(
		);
	}
	
	public function sistemaAction()
	{
		// verifica a permissão do usuário
		$this->commonsPlugin()->verificaPermissao('administrador');
		
		return array(
		);
	}
	
	////////////////////////////////
	// tables - todas do sistema! //
	////////////////////////////////
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
	
	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Usuario\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}
}
