<?php
namespace Missao\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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
	
	public function getMissoesFiltered($idTipoMissao, $nome, $protocolo, $status) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $idTipoMissao && $idTipoMissao != 0) {
			$select->where(array('idTipoMissao' => $idTipoMissao));
		}
		if (null !== $nome && $nome != '') {
			$select->where->like('nome', '%'.$nome.'%');
		}
		if (null !== $protocolo && $protocolo != 0) {
			$select->where(array('protocolo' => $protocolo));
		}
		if (null !== $status && $status != 'qualquer') {
			$select->where(array('status' => $status));
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foram localizados Missões com os parâmetros passados.");
		}
		return $resultSet;
	}
	
	public function getMissaoByProtocolo($protocolo) {
		//$protocolo  = (int) $protocolo;
		$rowset = $this->tableGateway->select(array('protocolo' => $protocolo));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizada Missao de protocolo ".$protocolo);
		}
		return $row;
	}
	
	public function getMissaoByIdAcidente($idAcidente){
		$idAcidente  = (int) $idAcidente;
		$rowset = $this->tableGateway->select(array('idAcidente' => $idAcidente));
		if (!$rowset) {
			throw new \Exception("Não foi localizado Missao de idAcidente ".$idAcidente);
		}
		return $rowset;		
	}
	
	public function saveMissao(Missao $Missao) {
		$data = array(
			'nome' => $Missao->nome,
			'idTipoMissao'  => $Missao->idTipoMissao,
			'protocolo'  => $Missao->protocolo,
			'status'  => $Missao->status,
			'recursosAlocados' => $Missao->recursosAlocados,
			'dataCriacao' => $Missao->dataCriacao,
			'idAcidente'  => $Missao->idAcidente,
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
	
	///////////////////////////////////////
	// funções para geração de relatório //
	///////////////////////////////////////
	public function getMissoesCadastradas($dataDe, $dataAte) {
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