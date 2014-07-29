<?php
namespace Missao\Model;

use Zend\Db\TableGateway\TableGateway;

class MissaoTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getMissao($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Missao de id ".$id);
		}
		return $row;
	}

	public function getMissaoByProtocolo($protocolo) {
		$protocolo  = (int) $protocolo;
		$rowset = $this->tableGateway->select(array('protocolo' => $protocolo));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizada Missao de protocolo ".$protocolo);
		}
		return $row;
	}
	
	public function saveMissao(Missao $Missao) {
		$data = array(
			'nome' => $Missao->nome,
			'idTipoMissao'  => $Missao->idTipoMissao,
			'protocolo'  => $Missao->protocolo,
			'status'  => $Missao->status,
			'recursosAlocados' => $Missao->recursosAlocados,
		);
		
		$id = (int) $Missao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getMissao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Missao de id '.$id.' não existe!');
			}
		}
	}
	
	public function atualizarStatusMissao($id, $status) {
		if ($this->getMissao($id)) {
			$data = array(
				'status'  => $status,
			);
			$this->tableGateway->update($data, array('id' => $id));
		} else {
			throw new \Exception('Missao de id '.$id.' não existe!');
		}
	}
	
	public function deleteMissao($id) {
		if ($this->getMissao($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Missao de id '.$id.' não existe!');
		}
	}
}