<?php
namespace Acidente\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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

	public function getAcidentesFiltered($localizacao, $descricao, $dataDe, $dataAte, $bombeiro,
			$policia, $numeroVitimas, $obstrucao) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $localizacao && $localizacao != '') {
			$select->where->like('localizacao', '%'.$localizacao.'%');
		}
		if (null !== $descricao && $descricao != '') {
			$select->where->like('descricao', '%'.$descricao.'%');
		}
		if (null !== $dataDe && $dataDe != 0) {
			$select->where->greaterThanOrEqualTo('data', date('Y-m-d H:i:s', strtotime($dataDe.' 00:00:00')));
		}
		if (null !== $dataAte && $dataAte != 0) {
			$select->where->lessThanOrEqualTo('data', date('Y-m-d H:i:s', strtotime($dataAte.' 23:59:59')));
		}
		if (null !== $bombeiro && $bombeiro != 0) {
			$select->where(array('bombeiro' => $bombeiro));
		}
		if (null !== $policia && $policia != 0) {
			$select->where(array('policia' => $policia));
		}
		if (null !== $numeroVitimas && $numeroVitimas != '') {
			$select->where->greaterThanOrEqualTo('numeroVitimas', $numeroVitimas);
		}
		if (null !== $obstrucao && $obstrucao != '') {
			$select->where->greaterThanOrEqualTo('obstrucao', $obstrucao);
		}
		//throw new \Exception($this->tableGateway->getSql()->getSqlstringForSqlObject($select));
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foram localizados Acidentes com os parâmetros passados.");
		}
		return $resultSet;
	}
	
	public function saveAcidente(Acidente $acidente) {
		$data = array(
			'localizacao' => $acidente->localizacao,
			'descricao' => $acidente->descricao,
			'data' => $acidente->data,
			'numeroVitimas' => $acidente->numeroVitimas,
			'bombeiro' => $acidente->bombeiro,
			'policia' => $acidente->policia,
			'obstrucao' => $acidente->obstrucao,
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
}