<?php
namespace Missao\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Recurso implements InputFilterAwareInterface
{
	// Atributos
	public $id;
	public $quantidade;
	public $idTipoRecurso;
	public $idMissao;

	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->quantidade = (!empty($data['quantidade'])) ? $data['quantidade'] : 0;
		$this->idTipoRecurso  = (!empty($data['idTipoRecurso'])) ? ($data['idTipoRecurso']) : null;
		$this->idMissao  = (!empty($data['idMissao'])) ? ($data['idMissao']) : null;
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
				'name'	 => 'quantidade',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),

				),
			));

			$inputFilter->add(array(
				'name'	 => 'idTipoRecurso',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));
			
			$inputFilter->add(array(
				'name'	 => 'idMissao',
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