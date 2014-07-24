<?php
namespace TipoRecurso\Model;

use Zend\Db\TableGateway\TableGateway;

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
			throw new \Exception("Não foi localizado Recurso de id $id");
		}
		return $row;
	}

	public function saveTipoRecurso(TipoRecurso $tipoRecurso) {
		$data = array(
			'nome' => $tipoRecurso->nome,
		);

		$id = (int) $tipoRecurso->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getTipoRecurso($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Recurso de id $id não existe!');
			}
		}
	}

	public function deleteTipoRecurso($id) {
		if ($this->getTipoRecurso($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Recurso de id $id não existe!');
		}
	}
}