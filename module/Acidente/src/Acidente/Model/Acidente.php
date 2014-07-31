<?php
namespace Acidente\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Acidente implements InputFilterAwareInterface
{
	// Atributos
    public $idAcidente;
    public $localizacao;
    public $descricao;
	public $data;
	public $numeroVitimas;
    public $bombeiro;
    public $policia;
    public $obstrucao;
	
	// Filtro para validações
	protected $inputFilter;

	public function exchangeArray($data)
    {
        $this->idAcidente     = (!empty($data['idAcidente'])) ? $data['idAcidente'] : null;
        $this->localizacao = (!empty($data['localizacao'])) ? $data['localizacao'] : null;
        $this->descricao  = (!empty($data['descricao'])) ? ($data['descricao']) : null;
		$this->data  = (!empty($data['data'])) ? $data['data'] : null;
		$this->numeroVitimas  = (!empty($data['numeroVitimas'])) ? $data['numeroVitimas'] : null;
        $this->bombeiro  = (!empty($data['bombeiro'])) ? $data['bombeiro'] : null;
        $this->policia  = (!empty($data['policia'])) ? $data['policia'] : null;
        $this->obstrucao  = (!empty($data['obstrucao'])) ? $data['obstrucao'] : null;
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
			
			// idAcidente
            $inputFilter->add(array(
                'name'     => 'idAcidente',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
			// localizacao
            $inputFilter->add(array(
                'name'     => 'localizacao',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
			
			// descricao
			$inputFilter->add(array(
                'name'     => 'descricao',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            // numeroVitimas
            $inputFilter->add(array(
                'name'     => 'numeroVitimas',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
            // bombeiro
            $inputFilter->add(array(
                'name'     => 'bombeiro',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Boolean'),
                ),
            ));
            
            // policia
            $inputFilter->add(array(
                'name'     => 'policia',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Boolean'),
                ),
            ));

            // obstrucao
            $inputFilter->add(array(
                'name'     => 'obstrucao',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
            $this->inputFilter = $inputFilter;
        }
		
        return $this->inputFilter;
    }
	
}