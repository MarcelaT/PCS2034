<?php
namespace TipoRecurso\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class TipoRecursoTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

    public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getTipoRecurso($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado TipoRecurso de id ".$id);
		}
		return $row;
	}
	
	public function getTipoRecursoFiltered($nome) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $nome && $nome != '') {
			$select->where->like('nome', '%'.$nome.'%');
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foram localizados TipoRecurso com os parâmetros passados.");
		}
		return $resultSet;
	}
	
	public function saveTipoRecurso(TipoRecurso $tipoRecurso) {
		$data = array(
			'nome' => $tipoRecurso->nome,
			'dataCriacao' => $tipoRecurso->dataCriacao,
		);

		$id = (int) $tipoRecurso->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getTipoRecurso($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('TipoRecurso de id '.$id.' não existe!');
			}
		}
	}

	public function deleteTipoRecurso($id) {
		if ($this->getTipoRecurso($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('TipoRecurso de id '.$id.' não existe!');
		}
	}
	
	public function getArrayNomes() {
		$tiposRecurso = $this->fetchAll();
		foreach($tiposRecurso as $tipoRecurso) {
			$nome[$tipoRecurso->id] = $tipoRecurso->nome;
		}		
		return $nome;		
	}
	
	
	///////////////////////////////////////
	// funções para geração de relatório //
	///////////////////////////////////////
	public function getTiposRecursoCadastrados($dataDe, $dataAte) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $dataDe && $dataDe != '' && $dataDe != 'Início') {
			$select->where->greaterThanOrEqualTo('dataCriacao', date('Y-m-d H:i:s', strtotime($dataDe.' 00:00:00')));
		}
		if (null !== $dataAte && $dataAte != '' && $dataAte != 'Agora') {
			$select->where->lessThanOrEqualTo('dataCriacao', date('Y-m-d H:i:s', strtotime($dataAte.' 23:59:59')));
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foi possível executar a consulta com os parâmetros passados.");
		}
		return $resultSet;
	}
}