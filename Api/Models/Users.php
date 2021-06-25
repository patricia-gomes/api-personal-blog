<?php

namespace Api\Models;

use Api\Core\Model;
use Api\Models\Jwt;

class Users extends Model
{

	private $id_user;

	public function check_login($email, $password)
	{

		$query = $this->pdo->prepare("SELECT id, password FROM users WHERE email = :email");
		$query->bindValue(':email', $email);
		$query->execute();

		if($query->rowCount() > 0) {
			$user = $query->fetch();

			if(password_verify($password, $user['password'])) {
				$this->id_user = $user['id'];

				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function get_ID() {
		return $this->id_user;
	}

	public function create_jwt()
	{
		$jwt = new Jwt();

		return $jwt->create(array('id_user' => $this->id_user));
	}

	public function validate_jWT($token)
	{
		$jwt = new Jwt();

		$dados = $jwt->validate($token);

		if(isset($dados->id_user)) {
			$this->id_user = $dados->id_user;
			return true;
		} else {
			return false;
		}
	}

	public function edit_categorie($id_user, $categorie, $id_cat)
	{
		//Verificando se o user tem permisao para alterar os dados
		if($id_user == $this->get_ID()) {
			$query = $this->pdo->prepare("UPDATE categories SET categorie = :categorie WHERE id = :id");
			$query->bindValue(':id', $id_cat);
			$query->bindValue(':categorie', $categorie);
			$query->execute();
			return 'Alterado com sucesso!!';
		} else {
			return 'Nao tem permissao para alterar essa informaçao!!';
		}
		
	}

	public function delete_categorie($id_user, $id_cat)
	{	
		//Verificando se o user tem permisao para deletar os dados
		if($id_user == $this->get_ID()) {
			$query = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
			$query->bindValue(':id', $id_cat);
			$query->execute();
			return 'Deletado com sucesso!!';
		} else {
			return 'Nao tem permissao para deletar essa informaçao!!';
		}
	}
} 