<?php

namespace Acidente\Form;

use Zend\Form\Form;
use Zend\Form\Form\Element\Date;
use Zend\Form\Form\Element\Checkbox;
use Zend\Form\Form\Element\Number;

class AcidenteForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('acidente');

		$this->add(array(
            'name' => 'idAcidente',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'localizacao',
            'type' => 'Text',
            'options' => array(
                'label' => 'Localizacao',
            )
        ));
        $this->add(array(
            'name' => 'descricao',
            'type' => 'Text',
            'options' => array(
                'label' => 'Descricao',
            )
        ));
        $this->add(array(
            'name' => 'data',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => 'Data',
            ),
            'attributes' => array(
             'min' => '2014-01-01',
             'max' => '2050-01-01',
             'step' => '1', // days; default step interval is 1 day
            )
        ));
		$this->add(array(
            'name' => 'bombeiro',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Bombeiro',
                'use_hidden_element' => true,
                'checked_value' => 'true',
                'unchecked_value' => 'false'
            )
        ));
		$this->add(array(
            'name' => 'policia',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Policia',
                'use_hidden_element' => true,
                'checked_value' => 'true',
                'unchecked_value' => 'false'
            )
        ));
		$this->add(array(
            'name' => 'numeroVitimas',
            'type' => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Numero de Vítimas',
            ),
            'attributes' => array(
             'min' => '0',
             'max' => '100',
             'step' => '1', // default step interval is 1
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            )
        ));
    }
}
