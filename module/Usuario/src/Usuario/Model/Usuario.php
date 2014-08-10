<?php
namespace Usuario\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Usuario implements InputFilterAwareInterface
{
	// Atributos
	public $id;
	public $login;
	public $senha;
	public $permissao;
	public $nome;
	public $email;
	public $dataCriacao;
	public $dataEdicao;
	public $permissaoNome;
	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->login = (!empty($data['login'])) ? $data['login'] : null;
		$this->senha  = (!empty($data['senha'])) ? $data['senha'] : null;
		$this->permissao = (!empty($data['permissao'])) ? $data['permissao'] : null;
		$this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
		$this->email = (!empty($data['email'])) ? $data['email'] : null;
		$this->dataCriacao  = (!empty($data['dataCriacao'])) ? $data['dataCriacao'] : null;
		$this->dataEdicao  = (!empty($data['dataEdicao'])) ? $data['dataEdicao'] : null;
		$this->permissaoNome  = (!empty($data['permissao'])) ? $this->getNomePermissao($data['permissao']) : null;
	}

	// atribui um nome user-friendly ao enum de permissão
	public function getNomePermissao($permissao)
	{
		if ($permissao == 'administrador') {
			return 'Administrador';
		} else if ($permissao == 'coordenador') {
			return 'Coordenador';
		} else if ($permissao == 'especialista') {
			return 'Especialista';
		} else if ($permissao == 'lider_missao') {
			return 'Líder da Missão';
		} else {
			return '';
		}
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
			
			// login
			$inputFilter->add(array(
				'name'	 => 'login',
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
			
			// nome
			$inputFilter->add(array(
				'name'	 => 'nome',
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
			
			// email
			$inputFilter->add(array(
				'name'	 => 'email',
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
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
	
}
