<?php

namespace Acidente\Form;

use Zend\Form\Form;
use Zend\Form\Form\Element\Checkbox;

class AcidenteFilterForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('acidente');
		
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
                'label' => 'Quantidade de obstruções na via',
            ),
        ));
		
		// submit
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Filtrar',
                'id' => 'submitbutton',
            )
        ));
    }
}