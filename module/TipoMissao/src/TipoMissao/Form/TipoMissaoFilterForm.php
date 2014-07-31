<?php

namespace TipoMissao\Form;

use Zend\Form\Form;

class TipoMissaoFilterForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('tipoMissao');

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
		
		// descricao
		$this->add(array(
			'name' => 'descricao',
			'type' => 'Text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Descrição',
			),
		));
		
		// submit
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Filtrar',
				'id' => 'submitbutton',
			),
		));
	}
}
