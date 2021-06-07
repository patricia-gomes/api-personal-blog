<?php

namespace Api\Core;

use Api\Core\Config;

class Model extends Config {
	/*
	* Classe com conexao com o banco e CRUD
	* A propriedade $pdo esta como 'protected' porque vai ser utilizada em classes em Models
	*/
	protected $pdo;

	//Conexao com o banco de dados
	public function __construct() {
		try {
			$this->pdo = new \PDO("mysql:host=".Config::HOST.";dbname=".Config::DBNAME.";charset=".Config::CHARSET, Config::USER, Config::PASS);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			echo "ERROR: ".$e->getMessage();exit;
		}
	}

	//Seleciona todas as colunas de qualquer tabela
	public function Select_All($table) {
		$query = "SELECT * FROM {$table}";
		$query = $this->pdo->query($query);

		if($query->rowCount() > 0) {
			return $query->fetchAll(\PDO::FETCH_ASSOC);
		}
	}
	//Retorna a quantidade de registros de uma tabela
	public function rowCount($table) {
		$sql = "SELECT count(id) as qt FROM {$table}";
		$sql = $this->pdo->query($sql);

		if($sql->rowCount() > 0) {
			$quant = $sql->fetch();
			return $quant['qt'];
		} else {
			return 0;
		}
	}

	//Seleciona todos as colunas em qualquer tabela utilizando a clausula  where
	public function Select_With_Where($table, $where = array()) {
		//Verifica se a tabela e a clausula where foi enviada
		if(!empty($table) && !empty($where)) {
		$query = "SELECT * FROM {$table} WHERE ";
		
			foreach($where as $coluna => $value) {
				$query.="$coluna = '{$value}'";
			}
			$result =$this->pdo->query($query);
			//Verifica se existe algum dado cadastrado no banco
			if($result->rowCount() > 0) {
				$result = $result->fetchAll(\PDO::FETCH_ASSOC);
				return $result;
			}
		}
	}

	//Insere em qualquer tabela e em qualquer quantidade de coluna tiver essa tabela
	public function Insert($table, $colunas = array()) {
		$dados = array();
		//Perconrendo o array das colunas para pegar as colunas da tabela
		foreach($colunas as $coluna => $value) {
			$dados[] = "$coluna";
			$dados2[] = ":$coluna";
		}
		$query = $this->pdo->prepare("INSERT INTO {$table} (".implode(', ', $dados).") VALUES (".implode(', ', $dados2).")");

		/*Percorre o array das colunas para vincular o valor a coluna*/
		foreach($colunas as $coluna => $value) {
			$query->bindValue(":$coluna", "$value");
		}
		$query->execute();
	}

	//Edita um registro por vez em qualquer tabela
	public function Update_With_Where($table, $newDados = array(), $where = array()) {
		
		if(!empty($table) && !empty($newDados) && !empty($where)) {
			$query = "UPDATE {$table} SET ";
			/*Percorendo o array das colunas para pegar as colunas da tabela e montar a query*/
			foreach($newDados as $coluna => $value) {
				$dados[] = "$coluna = '$value'";
			}
			$query.= implode(', ', $dados);
			//Percorendo o array do where e concatenando com o restante da query
			foreach($where as $id => $value_id) {
				$query.=" WHERE $id = {$value_id}";
			}
			$this->pdo->query($query);
		}
	}

	/* ----Deleta um registro por vez com base no id em qualquer tabela----*/
	public function Delete_With_Where($table, $where) {

		//Verifica se as variaveis nao estao vazias
		if(!empty($table) && !empty($where)) {

			$query = "DELETE FROM {$table} WHERE ";
			foreach ($where as $coluna => $value) {
				$query.= "$coluna = {$value}";
			}
			$this->pdo->query($query);
		}
	}
} 