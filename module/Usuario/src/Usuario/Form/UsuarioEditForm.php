<?php

namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioEditForm extends Form
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
		
		// permissao
		$this->add(array(
			'name' => 'permissao',
			'type' => 'select',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Permissão',
				'value_options' => array(
					'administrador' => 'Administrador',
					'coordenador' => 'Coordenador',
					'especialista' => 'Especialista',
					'lider_missao' => 'Líder da Missão',
				),
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
