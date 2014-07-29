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
	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id	 = (!empty($data['id'])) ? $data['id'] : null;
		$this->idTipoMissao = (!empty($data['idTipoMissao'])) ? $data['idTipoMissao'] : null;
		$this->protocolo  = (!empty($data['protocolo'])) ? ($data['protocolo']) : null;
		$this->status  = (!empty($data['status'])) ? ($data['status']) : null;
		$this->recursosAlocados  = (!empty($data['recursosAlocados'])) ? ($data['recursosAlocados']) : null;
		$this->nome  = (!empty($data['nome'])) ? ($data['nome']) : null;

	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
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

			$inputFilter->add(array(
				'name'	 => 'nome',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$inputFilter->add(array(
				'name'	 => 'recursosAlocados',
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
					array('name' => 'Int'),
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
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
	
}