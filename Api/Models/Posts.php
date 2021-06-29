<?php
namespace Api\Models;

use Api\Core\Model;

class Posts extends Model
{

	public function all_posts() {
		$query = $this->pdo->prepare("SELECT * FROM posts");
		$query->execute();

		if($query->rowCount() > 0) {
			return $query->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function new_post($title, $tutorial, $date_time, $id_categorie, $id_user) {

		$query = $this->pdo->prepare("INSERT INTO posts (title, tutorial, date_time, id_categorie, id_author) VALUES (:title, :tutorial, :date_time, :id_categorie, :id_author)");
		$query->bindValue(':title', $title);
		$query->bindValue(':tutorial', $tutorial);
		$query->bindValue(':date_time', $date_time);
		$query->bindValue(':id_categorie', $id_categorie);
		$query->bindValue(':id_author', $id_user);
		$query->execute();

		return 'Cadastrado com sucesso!!';
	}

	public function edit_post($dados) {

		$to_change = array();

		if(!empty($dados['title'])) {
			$to_change['title'] = $dados['title']; 
		}
		if(!empty($dados['tutorial'])) {
			$to_change['tutorial'] = $dados['tutorial']; 
		}
		if(!empty($dados['id_categorie'])) {
			$to_change['id_categorie'] = $dados['id_categorie'];
		}
		if(!empty($dados['id'])) {
			$to_change['id'] = $dados['id'];
		}

		if(count($to_change) > 0) {

			$fields = array();

			foreach($to_change as $key => $value) {
				$fields[] = $key. ' = :'.$key;
			}

			$query = "UPDATE posts SET ".implode(',', $fields)." WHERE id = :id ";
			$query = $this->pdo->prepare($query);
			
			foreach($to_change as $key => $value) {
				$query->bindValue(':'.$key, $value);
			}

			$query->execute();
			return 'Atualizado com sucesso!!';
		} else {
			return 'Preencha todos os campos!!';
		}
	}

	public function delete_post($id) {

		$query = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
		$query->bindValue(':id', $id);
		$query->execute();

		return 'Deletado com sucesso!!';
	}
}