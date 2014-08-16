<?php
namespace Missao\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class EditarRecurso implements InputFilterAwareInterface
{
	// Atributos
	public $idTipoRecurso;
	public $quantidade;
	public $nome;
	public $idRecurso;

	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->idTipoRecurso = (!empty($data['idTipoRecurso'])) ? $data['idTipoRecurso'] : null;
		$this->idRecurso = (!empty($data['idRecurso'])) ? $data['idRecurso'] : null;
		$this->quantidade = (!empty($data['quantidade'])) ? $data['quantidade'] : 0;
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
				'name'	 => 'idTipoRecurso',
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
				'name'	 => 'nome',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$inputFilter->add(array(
				'name'	 => 'idRecurso',
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