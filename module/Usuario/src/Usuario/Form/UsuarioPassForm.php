<?php

namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioPassForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('usuario');

		// id
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));

		// senha antiga
		$this->add(array(
			'name' => 'senha-antiga',
			'type' => 'password',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Senha Antiga',
			),
		));
		
		// senha nova
		$this->add(array(
			'name' => 'senha-nova',
			'type' => 'password',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Nova Senha',
			),
		));
		
		// senha nova check
		$this->add(array(
			'name' => 'senha-nova-chk',
			'type' => 'password',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Digite a nova senha outra vez',
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
