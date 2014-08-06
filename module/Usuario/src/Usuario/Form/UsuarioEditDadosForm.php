<?php

namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioEditDadosForm extends Form
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
		
		// login
		$this->add(array(
			'name' => 'login',
			'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Login',
			),
		));
		
		// nome
		$this->add(array(
			'name' => 'nome',
			'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Nome',
			),
		));
		
		// email
		$this->add(array(
			'name' => 'email',
			'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Email',
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
