<?php

namespace TipoRecurso\Form;

use Zend\Form\Form;

class TipoRecursoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('tipoRecurso');

		$this->add(array(
            'name' => 'id',
            'type' => 'text',
        ));
        $this->add(array(
            'name' => 'nome',
            'type' => 'Text',
            'options' => array(
                'label' => 'Nome',
            ),
        ));
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
