<?php
namespace Missao\Model;

use Zend\Db\TableGateway\TableGateway;

class RecursoTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getRecurso($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Recurso de id ".$id);
		}
		return $row;
	}

	public function getRecursosidMissao($idMissao) {
		$idMissao  = (int) $idMissao;
		$resultSet = $this->tableGateway->select(array('idMissao' => $idMissao));
		if (!$resultSet) {
			throw new \Exception("Não foi localizado Recurso de idMissao ".$idMissao);
		}
		return $resultSet;
	}

	public function saveRecurso(Recurso $Recurso) {
		$data = array(
			'quantidade' => $Recurso->quantidade,
			'idTipoRecurso' => $Recurso->idTipoRecurso,
			'idMissao' => $Recurso->idMissao,
			'dataCriacao' => $Recurso->dataCriacao,
		);
		
		$id = (int) $Recurso->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getRecurso($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Recurso de id '.$id.' não existe!');
			}
		}
	}

	public function deleteRecurso($id) {
		if ($this->getRecurso($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Recurso de id '.$id.' não existe!');
		}
	}
}