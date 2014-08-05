<?php
namespace Missao\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RecursoNome implements InputFilterAwareInterface
{
	// Atributos
	public $id;
	public $nome;
	public $quantidade;

	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->quantidade = (!empty($data['quantidade'])) ? $data['quantidade'] : 0;
		$this->nome = (!empty($data['nome'])) ? ($data['nome']) : null;

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

			// quantidade
			$inputFilter->add(array(
				'name'	 => 'quantidade',
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
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
	
}