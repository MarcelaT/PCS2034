<?php
namespace Missao\Form;

use Zend\Form\Form;

class NovoRecursoForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('missao');
		
		// id
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));
		
		
		// tipoDeMissao
		$this->add(array(
			'name' => 'idTipoRecurso',
			'type' => 'select',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Tipo de recurso',
			),
		));
		
		//quantidade
		$this->add(array(
			'name' => 'quantidade',
			'type' => 'Text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Quantidade',
			),
		));

		//quantidade
		$this->add(array(
			'name' => 'idMissao',
			'type' => 'Text',
			'attributes' => array(
				'class' => 'hidden',
			),
			'options' => array(
				'label' => 'idMissao',
			),
		));

		// submit
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Alocar missão',
				'id' => 'submitbutton',
			),
		));
	}
}
