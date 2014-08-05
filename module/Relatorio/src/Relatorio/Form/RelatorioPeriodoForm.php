<?php

namespace Relatorio\Form;

use Zend\Form\Form;

class RelatorioPeriodoForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('relatorio');

		// dataDe
        $this->add(array(
            'name' => 'dataDe',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
				'id' => 'dataAte',
			),
            'options' => array(
                'label' => 'De (Data)',
            )
        ));
		
		// dataAte
        $this->add(array(
            'name' => 'dataAte',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
				'id' => 'dataAte',
			),
            'options' => array(
                'label' => 'Até (Data)',
            )
        ));
		
		//submit
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
