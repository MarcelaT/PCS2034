<?php

namespace Missao\Form;

use Zend\Form\Form;

class MissaoFilterForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('Missao');
		
		// tipoDeMissao
		$this->add(array(
			'name' => 'idTipoMissao',
			'type' => 'select',
			'attributes' => array(
				'class' => 'form-control',
			),
			'options' => array(
				'label' => 'Tipo de missão',
				'value_options' => array(
					'qualquer' => 'Qualquer',
				),
			),
		));
		
		// nome
		$this->add(array(
			'name' => 'nome',
			'type' => 'text',
			'options' => array(
				'label' => 'Nome',
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
					'qualquer' => 'Qualquer',
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
				'value' => 'Filtrar',
				'id' => 'submitbutton',
			),
		));
	}
}
