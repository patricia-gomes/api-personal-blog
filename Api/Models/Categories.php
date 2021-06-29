<?php
namespace Api\Models;

use Api\Core\Model;

class Categories extends Model
{


	public function new_categorie($categorie) {

		$query = $this->pdo->prepare("INSERT INTO categories (categorie) VALUES (:categorie)");
		$query->bindValue(':categorie', $categorie);
		$query->execute();
		return true;
	}

	public function get_categories() {
		$dados = array();

		$query = $this->pdo->prepare("SELECT * FROM categories");
		$query->execute();

		if($query->rowCount() > 0) {
			return $query->fetchAll(\PDO::FETCH_ASSOC);
		}
		return $dados;
	}
}