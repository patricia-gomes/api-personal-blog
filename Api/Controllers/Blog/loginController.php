<?php
namespace Api\Controllers\Blog;

use Api\Core\Controller;
use Api\Models\Users;

class loginController extends Controller
{

	public function index() { 
		$array = array('error'=>'');

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		//Verifica se o método selecionado é o correto
		if($method == 'POST') {
			//Verificando se o user enviou o email e a senha
			if(!empty($dados['email']) && !empty($dados['password'])) {
				$user = new Users();

				if($user->check_login($dados['email'], $dados['password'])) {
					$array['jwt'] = $user->create_jwt();
				} else {
					$array['error'] = 'Acesso negado!';
				}

			} else {
				$array['error'] = 'Preecha todos os campos, por favor!';
			}
		} else {
			$array['error'] = 'Método de requisição incorreto!';
		}

		$this->return_json($array);
	}
	
}