<?php
namespace Acidente\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Acidente implements InputFilterAwareInterface
{
	// Atributos
	public $idAcidente;
	public $localizacao;
	public $descricao;
	public $data;
	public $numeroVitimas;
	public $bombeiro;
	public $policia;
	public $obstrucao;
	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->localizacao = (!empty($data['localizacao'])) ? $data['localizacao'] : null;
		$this->descricao = (!empty($data['descricao'])) ? ($data['descricao']) : null;
		$this->data = (!empty($data['data'])) ? $data['data'] : null;
		$this->numeroVitimas  = (!empty($data['numeroVitimas'])) ? $data['numeroVitimas'] : 0;
		$this->bombeiro = (!empty($data['bombeiro'])) ? $data['bombeiro'] : 0;
		$this->policia = (!empty($data['policia'])) ? $data['policia'] : 0;
		$this->obstrucao  = (!empty($data['obstrucao'])) ? $data['obstrucao'] : 0;
		$this->status  = (!empty($data['status'])) ? $data['status'] : 0;

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
			
			// idAcidente
			$inputFilter->add(array(
				'name'	 => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			));
			
			// localizacao
			$inputFilter->add(array(
				'name'	 => 'localizacao',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'	=> 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'	  => 1,
							'max'	  => 100,
						),
					),
				),
			));
			
			// descricao
			$inputFilter->add(array(
				'name'	 => 'descricao',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'	=> 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'	  => 1,
							'max'	  => 100,
						),
					),
				),
			));
			
			// numeroVitimas
			$inputFilter->add(array(
				'name' => 'numeroVitimas',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
				'validators' => array(
					array(
						'name' => 'Between',
						'options' => array(
							'min' => 0,
							'max' => 100,
						),
					),
				),
			));
			
			// obstrucao
			$inputFilter->add(array(
				'name' => 'obstrucao',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
				'validators' => array(
					array(
						'name' => 'Between',
						'options' => array(
							'min' => 0,
							'max' => 100,
						),
					),
				),
			));

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