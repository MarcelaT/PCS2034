<?php

namespace Missao\Form;

use Zend\Form\Form;

class MissaoStatusForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('Missao');

		// protocolo
		$this->add(array(
			'name' => 'protocolo',
			'type' => 'text',
			'options' => array(
				'label' => 'Protocolo',
			),
		));
		
		//submit
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
