<?php

namespace TipoRecurso\Form;

use Zend\Form\Form;

class TipoRecursoForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('tipoRecurso');

		// id
		$this->add(array(
			'name' => 'id',
			'type' => 'text',
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
