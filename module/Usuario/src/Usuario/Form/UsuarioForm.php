<?php

namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('usuario');

		$this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'login',
            'type' => 'Text',
            'options' => array(
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'senha',
            'type' => 'Text',
            'options' => array(
                'label' => 'Senha',
            ),
        ));
		$this->add(array(
            'name' => 'permissao',
            'type' => 'Text',
            'options' => array(
                'label' => 'Permissão',
            ),
        ));
		$this->add(array(
            'name' => 'nome',
            'type' => 'Text',
            'options' => array(
                'label' => 'Nome',
            ),
        ));
		$this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
