<?php

namespace Missao\Form;

use Zend\Form\Form;

class MissaoForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('Missao');

		// id
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));
		
		// nome
		$this->add(array(
			'name' => 'nome',
			'type' => 'Text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Nome',
			),
		));
		
		// tipoDeMissao
		$this->add(array(
			'name' => 'idTipoMissao',
			'type' => 'select',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Tipo de missão',
			),
		));
		
		// submit
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Enviar',
				'id' => 'submitbutton',
			),
		));
	}
}
