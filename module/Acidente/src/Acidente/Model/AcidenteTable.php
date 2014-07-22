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

	public function getAcidente($idAcidente) {
		$idAcidente  = (int) $idAcidente;
		$rowset = $this->tableGateway->select(array('idAcidente' => $idAcidente));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Acidente de id $idAcidente");
		}
		return $row;
	}

	public function saveAcidente(Acidente $acidente) {
		$data = array(
			'localizacao' => $acidente->localizacao,
			'descricao'  => $acidente->descricao,
			'data'  => $acidente->data,
			'numeroVitimas'  => $acidente->numeroVitimas,
			'bombeiro'  => $acidente->bombeiro,
			'policia'  => $acidente->policia,
			'obstrucao'  => $acidente->obstrucao,
		);

		$idAcidente = (int) $acidente->idAcidente;
		if ($idAcidente == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getAcidente($idAcidente)) {
				$this->tableGateway->update($data, array('idAcidente' => $idAcidente));
			} else {
				throw new \Exception('Acidente de id $idAcidente não existe!');
			}
		}
	}

	public function deleteAcidente($idAcidente) {
		if ($this->getAcidente($id)) {
			$this->tableGateway->delete(array('idAcidente' => (int) $idAcidente));
		} else {
			throw new \Exception('Acidente de id $idAcidente não existe!');
		}
	}
}