<?php

namespace Missao\Form;

use Zend\Form\Form;

class MissaoForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('Missao');

		$this->add(array(
			'name' => 'id',
			'type' => 'text',
		));
		$this->add(array(
			'name' => 'idTipoMissao',
			'type' => 'Text',
			'options' => array(
				'label' => 'idTipoMissao',
			),
		));
		$this->add(array(
			'name' => 'protocolo',
			'type' => 'Text',
			'options' => array(
				'label' => 'protocolo',
			),
		));
		$this->add(array(
			'name' => 'status',
			'type' => 'Text',
			'options' => array(
				'label' => 'protocolo',
			),
		));
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
