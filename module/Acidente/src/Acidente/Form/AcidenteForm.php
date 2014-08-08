<?php

namespace Acidente\Form;

use Zend\Form\Form;
use Zend\Form\Form\Element\Checkbox;
use Zend\Form\Form\Element\Number;

class AcidenteForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('acidente');

		// id
		$this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
		
		// localizacao
        $this->add(array(
            'name' => 'localizacao',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Localização',
            )
        ));
		
		// descricao
        $this->add(array(
            'name' => 'descricao',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Descrição',
            )
        ));
		
		// bombeiro
		$this->add(array(
            'name' => 'bombeiro',
            'type' => 'checkbox',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Bombeiro',
            )
        ));
		
		// policia
		$this->add(array(
            'name' => 'policia',
            'type' => 'checkbox',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Polícia',
            )
        ));
		
		// numeroVitimas
		$this->add(array(
            'name' => 'numeroVitimas',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Número de vítimas',
            ),
        ));
		
		// obstrucao
        $this->add(array(
            'name' => 'obstrucao',
            'type' => 'text',
			'attributes' => array(
				'class' => 'form-control',
			),
            'options' => array(
                'label' => 'Número de obstruções na via',
            ),
        ));

                // status
        $this->add(array(
            'name' => 'status',
            'type' => 'text',
            'attributes' => array(
                'class' => 'hidden',
                'value' => 'cadastrado',
            ),
            'options' => array(
                'label' => 'Status',
            ),
        ));
		
		// submit
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Enviar',
                'id' => 'submitbutton',
            )
        ));
    }
}
