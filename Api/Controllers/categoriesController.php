<?php
namespace Api\Controllers;

use Api\Core\Controller;
use Api\Models\Users;
use Api\Models\Categories;

class categoriesController extends Controller
{
	//Exibe todas as categorias cadastradas, atualiza e deleta
	public function index()
	{
		$array = array('error'=> '', 'logged'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			$cat = new Categories();
			//Verifica o metodo
			if($method == 'GET') {
				$array['categories'] = $cat->get_categories();
			} else {
				$array['error'] = 'Metodo indisponivel';
			}

		} else {
			$array['error'] = 'Acesso negado';
		}

		$this->return_json($array);
	}

	public function register($id_user)
	{
		$array = array('error'=> '', 'logged'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			$array['is_me'] = false;
			if($id_user == $user->get_ID()) {
				$array['is_me'] = true;
			}

			//Verifica se o metodo é post
			if($method == 'POST') {
				//Verifica se nao esta vazio
				if(!empty($dados['categorie'])) {
					//Cadastra
					$categories = new Categories();
					$categories->new_categorie($dados['categorie']);

					$array['register'] = 'Cadastrado com sucesso!!';

				} else {
					$array['error'] = 'Preencha o campo!';
				}
			} else {
				$array['error'] = 'Metodo '.$method.' indisponivel';
			}
		} else {
			$array['error'] = 'Acesso negado';
		}

		$this->return_json($array);
	}

	public function edit($id_user)
	{
		$array = array('error'=> '', 'logged'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();	

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			if($method == 'PUT') {
				//Verifica se foi enviada a nova categoria
				if(!empty($dados['categorie']) && !empty($dados['id'])) { 
					$array['edit'] = $user->edit_categorie($id_user, $dados['categorie'], $dados['id']);
				} else {
					$array['error'] = 'Preencha o campo!';
				}
			} else {
				$array['error'] = 'Metodo indisponivel';
			}

		} else {
			$array['error'] = 'Acesso negado';
		}
		
		$this->return_json($array);
	}

	public function delete($id_user)
	{
		$array = array('error'=> '', 'logged'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();	

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			if($method == "DELETE") {
				//Verifica se o id da categoria foi enviada
				if(!empty($dados['id'])) {
					$array['delete'] = $user->delete_categorie($id_user, $dados['id']);
				}
			} else {
				$array['error'] = 'Metodo indisponivel';
			}
		}

		$this->return_json($array);
	}
}