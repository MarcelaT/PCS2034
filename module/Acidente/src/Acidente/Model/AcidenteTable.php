<?php
namespace Acidente\Model;

use Zend\Db\TableGateway\TableGateway;

class AcidenteTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

    public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getAcidente($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Acidente de id ".$id);
		}
		return $row;
	}

	public function saveAcidente(Acidente $acidente) {
		$data = array(
			'localizacao' => $acidente->localizacao,
			'descricao'  => $acidente->descricao,
			'data' => $acidente->data,
			'numeroVitimas'  => $acidente->numeroVitimas,
			'bombeiro'  => $acidente->bombeiro,
			'policia'  => $acidente->policia,
			'obstrucao'  => $acidente->obstrucao,
			'status'  => $acidente->status,

		);

		$id = (int) $acidente->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getAcidente($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Acidente de id '.$id.' não existe!');
			}
		}
	}

	public function deleteAcidente($id) {
		if ($this->getAcidente($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Acidente de id '.$id.' não existe!');
		}
	}

	public function atualizarStatusAcidente($id, $status) {
		if ($this->getAcidente($id)) {
			$data = array(
				'status'  => $status,
			);
			$this->tableGateway->update($data, array('id' => $id));
		} else {
			throw new \Exception('Acidente de id '.$id.' não existe!');
		}
	}
}