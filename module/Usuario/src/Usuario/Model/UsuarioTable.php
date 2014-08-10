<?php
namespace Usuario\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UsuarioTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getUsuario($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Usuário de id ".$id);
		}
		return $row;
	}

	public function getUsuarioByLogin($login) {
		$rowset = $this->tableGateway->select(array('login' => $login));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Usuário de login ".$login);
		}
		return $row;
	}
	
	public function getUsuariosFiltered($login, $nome, $email, $permissao) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $login && $login != '') {
			$select->where->like('login', '%'.$login.'%');
		}
		if (null !== $nome && $nome != '') {
			$select->where->like('nome', '%'.$nome.'%');
		}
		if (null !== $email && $email != '') {
			$select->where->like('email', '%'.$email.'%');
		}
		if (null !== $permissao && $permissao != 'qualquer') {
			$select->where(array('permissao' => $permissao));
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foram localizados Usuários com os parâmetros passados.");
		}
		return $resultSet;
	}
	
	public function saveUsuario(Usuario $usuario) {
		$data = array(
			'login' => $usuario->login,
			'senha'  => $usuario->senha,
			'permissao'  => $usuario->permissao,
			'nome'  => $usuario->nome,
			'email'  => $usuario->email,
			'dataCriacao'  => $usuario->dataCriacao,
			'dataEdicao'  => $usuario->dataEdicao,
		);

		$id = (int) $usuario->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getUsuario($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Usuário de id '.$id.' não existe!');
			}
		}
	}

	public function deleteUsuario($id) {
		if ($this->getUsuario($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Usuário de id '.$id.' não existe!');
		}
	}
	
	///////////////////////////////////////
	// funções para geração de relatório //
	///////////////////////////////////////
	public function getUsuariosCadastrados($dataDe, $dataAte) {
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
	
	public function getUsuariosEditados($dataDe, $dataAte) {
		$select = new Select();
		
		// verifica quais estão sendo realmente utilizados
		if (null !== $dataDe && $dataDe != '' && $dataDe != 'Início') {
			$select->where->greaterThanOrEqualTo('dataEdicao', date('Y-m-d H:i:s', strtotime($dataDe.' 00:00:00')));
		}
		if (null !== $dataAte && $dataAte != '' && $dataAte != 'Agora') {
			$select->where->lessThanOrEqualTo('dataEdicao', date('Y-m-d H:i:s', strtotime($dataAte.' 23:59:59')));
		}
		
		$resultSet = $this->tableGateway->select($select->where);
		if (!$resultSet) {
			throw new \Exception("Não foi possível executar a consulta com os parâmetros passados.");
		}
		return $resultSet;
	}
}