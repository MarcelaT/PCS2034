<?php
namespace TipoRecurso\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class TipoRecurso implements InputFilterAwareInterface
{
	// Atributos
    public $id;
    public $nome;


	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;

    }

	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
			
			// id
            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
			// protocolo
            $inputFilter->add(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));
					
			
            $this->inputFilter = $inputFilter;
        }
		
        return $this->inputFilter;
    }
	
}