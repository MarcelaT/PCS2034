<?php

namespace Missao\Form;

use Zend\Form\Form;

class AlocacaoRecursosForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('Missao');

		$this->add(array(
			'name' => 'idTipoRecurso',
			'type' => 'text',
		));
		$this->add(array(
			'name' => 'quantidade',
			'type' => 'Text',
			'options' => array(
				'label' => 'idTipoMissao',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Alocar',
				'id' => 'submitbutton',
			),
		));
	}
}
