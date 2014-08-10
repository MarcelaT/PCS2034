<?php
namespace TipoMissao\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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
			throw new \Exception("Não foi localizado TipoMissao de id ".$id);
		}
		return $row;
	}
	
	public function getTipoMissaoFiltered($nome, $descricao) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $nome && $nome != '') {
			$select->where->like('nome', '%'.$nome.'%');
		}
		if (null !== $descricao && $descricao != '') {
			$select->where->like('descricao', '%'.$descricao.'%');
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foram localizados TipoMissão com os parâmetros passados.");
		}
		return $resultSet;
	}
	
	public function saveTipoMissao(TipoMissao $tipoMissao) {
		$data = array(
			'nome' => $tipoMissao->nome,
			'descricao'  => $tipoMissao->descricao,
			'dataCriacao'  => $tipoMissao->dataCriacao,
		);

		$id = (int) $tipoMissao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getTipoMissao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('TipoMissao de id '.$id.' não existe!');
			}
		}
	}

	public function deleteTipoMissao($id) {
		if ($this->getTipoMissao($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('TipoMissao de id '.$id.' não existe!');
		}
	}
	
	public function getArrayNomes() {
		$tiposMissao = $this->fetchAll();
		foreach($tiposMissao as $tipoMissao) {
			$nome[$tipoMissao->id] = $tipoMissao->nome;
		}		
		return $nome;		
	}
	
	///////////////////////////////////////
	// funções para geração de relatório //
	///////////////////////////////////////
	public function getTiposMissaoCadastrados($dataDe, $dataAte) {
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