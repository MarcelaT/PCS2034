<?php
namespace TipoMissao\Model;

use Zend\Db\TableGateway\TableGateway;

class TipoMissaoTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

    public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getTipoMissao($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Missao de id $id");
		}
		return $row;
	}

	public function saveTipoMissao(TipoMissao $tipoMissao) {
		$data = array(
			'nome' => $tipoMissao->nome,
			'descricao'  => $tipoMissao->descricao,
		);

		$id = (int) $tipoMissao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getTipoMissao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Missao de id $id não existe!');
			}
		}
	}

	public function deleteTipoMissao($id) {
		if ($this->getTipoMissao($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Missao de id $id não existe!');
		}
	}
}