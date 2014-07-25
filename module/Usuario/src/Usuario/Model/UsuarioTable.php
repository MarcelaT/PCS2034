<?php
namespace Usuario\Model;

use Zend\Db\TableGateway\TableGateway;

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
			throw new \Exception("Não foi localizado Usuário de id $id");
		}
		return $row;
	}

	public function getUsuarioByLogin($login) {
		$rowset = $this->tableGateway->select(array('login' => $login));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Não foi localizado Usuário de id $id");
		}
		return $row;
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
				throw new \Exception('Usuário de id $id não existe!');
			}
		}
	}

	public function deleteUsuario($id) {
		if ($this->getUsuario($id)) {
			$this->tableGateway->delete(array('id' => (int) $id));
		} else {
			throw new \Exception('Usuário de id $id não existe!');
		}
	}
}