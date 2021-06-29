<?php
namespace Api\Controllers;

use Api\Core\Controller;
use Api\Models\Users;
use Api\Models\Posts;

class editorController extends Controller
{

	public function index($id_user) {
		$array = array('error'=> '', 'logged'=> false, 'is_me'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();
		$posts = new Posts();

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			//Verifica se o id_user enviado na url é o mesmo que esta logado
			if($id_user == $user->get_ID()) {
				$array['is_me'] = true;		

				//Verifica se o metodo é post
				if($method == 'POST') {

					if(!empty($dados['title']) && !empty($dados['tutorial']) && !empty($dados['date_time'])
						&& !empty($dados['id_categorie'])) {

						$array['posts'] = $posts->new_post($dados['title'], $dados['tutorial'], $dados['date_time'], $dados['id_categorie'], $id_user);
					} else {
						$array['error'] = 'Preencha os campos!';
					}
				} else {
					$array['error'] = 'Metodo indisponivel';
				}
			} else {
				$array['error'] = 'Você nao tem permissao para cadastrar!';
			}
		} else {
			$array['error'] = 'Acesso negado';
		}


		$this->return_json($array);
	}

	public function edit($id_user) {
		$array = array('error'=> '', 'logged'=> false, 'is_me'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();
		$posts = new Posts();

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			if($id_user == $user->get_ID()) {
				$array['is_me'] = true;
			
				if($method == 'PUT') {
					//Verifica se enviou o id do post
					if(!empty($dados['id'])) {
						//Caso o user enviar somente o jwt e o id
						if(count($dados) > 2) {
							$array['edit'] = $posts->edit_post($dados);
						} else {
							$array['error'] = 'Nessecita de mais um campo para fazer a atualizaçao!';
						}					
						
					} else {
						$array['error'] = 'Preencha o campo id';
					}										
				} else {
					$array['error'] = 'Metodo indisponivel';
				}
			} else {
				$array['error'] = 'Nao tem premissao para editar!!';
			}
		} else {
			$array['error'] = 'Acesso Negado!';
		}

		$this->return_json($array);
	}

	public function delete($id_user) {
		$array = array('error'=> '', 'logged'=> false, 'is_me'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();
		$posts = new Posts();

		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			if($id_user == $user->get_ID()) {
				$array['is_me'] = true;
				
				if($method == "DELETE") {

					if(!empty($dados['id'])) {
						$array['delete'] = $posts->delete_post($dados['id']);
					} else {
						$array['error'] = 'Preencha o campo id';
					}
				} else {
					$array['error'] = 'Metodo indisponivel';
				}
			} else {
				$array['error'] = 'Nao tem permissao para deletar!';
			}
		} else {
			$array['error'] = 'Acesso Negado!';
		} 	

		$this->return_json($array);
	}
}