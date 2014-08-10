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
			'type' => 'text',
			'options' => array(
				'label' => 'Nome',
			),
		));
		
		// idTipoMissao
		$this->add(array(
			'name' => 'idTipoMissao',
			'type' => 'text',
			'options' => array(
				'label' => 'idTipoMissao',
			),
		));
		
		// protocolo
		$this->add(array(
			'name' => 'protocolo',
			'type' => 'text',
			'options' => array(
				'label' => 'Protocolo',
			),
		));
		
		// status
		$this->add(array(
			'name' => 'status',
			'type' => 'select',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Status',
				'value_options' => array(
					'cadastrada' => 'Cadastrada',
					'em_andamento' => 'Em Andamento',
					'abortada' => 'Abortada',
					'concluida' => 'Concluída',
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
