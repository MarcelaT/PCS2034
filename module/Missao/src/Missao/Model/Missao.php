<?php
namespace Missao\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Missao implements InputFilterAwareInterface
{
	// Atributos
	public $id;
	public $idTipoMissao;
	public $protocolo;
	public $status;
	public $recursosAlocados;
	public $nome;
	public $dataCriacao;
	public $idAcidente;
	public $statusNome;
	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->idTipoMissao = (!empty($data['idTipoMissao'])) ? $data['idTipoMissao'] : null;
		$this->protocolo = (!empty($data['protocolo'])) ? ($data['protocolo']) : null;
		$this->status = (!empty($data['status'])) ? ($data['status']) : 0;
		$this->recursosAlocados = (!empty($data['recursosAlocados'])) ? ($data['recursosAlocados']) : false;
		$this->nome = (!empty($data['nome'])) ? ($data['nome']) : null;
		$this->dataCriacao = (!empty($data['dataCriacao'])) ? $data['dataCriacao'] : $this->getDataAtual();
		$this->idAcidente = (!empty($data['idAcidente'])) ? $data['idAcidente'] : null;
		$this->statusNome = (!empty($data['status'])) ? $this->getNomeStatus($data['status']) : null;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	// atribui um nome user-friendly ao enum de status
	public function getNomeStatus($status)
	{
		if ($status == 'cadastrada') {
			return 'Cadastrada';
		} else if ($status == 'em_andamento') {
			return 'Em andamento';
		} else if ($status == 'concluida') {
			return 'Concluida';
		} else if ($status == 'abortada') {
			return 'Abortada';
		} else {
			return '';
		}
	}
	
	// retorna a data atual
	public function getDataAtual() {
		date_default_timezone_set("Brazil/East");
		$dataAtual = date('Y-m-d H:i:s');
		return $dataAtual;
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			
			// id
			$inputFilter->add(array(
				'name'	 => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));
			
			// nome
			$inputFilter->add(array(
				'name'	 => 'nome',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));
			
			// idTipoMissao
			$inputFilter->add(array(
				'name'	 => 'idTipoMissao',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));
			
			// protocolo
			$inputFilter->add(array(
				'name'	 => 'protocolo',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			// status
			$inputFilter->add(array(
				'name'	 => 'status',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));
			
			//idAcidente
			$inputFilter->add(array(
				'name'	 => 'idAcidente',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));

			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
	
}