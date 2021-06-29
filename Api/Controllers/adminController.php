<?php
namespace Api\Controllers;

use Api\Core\Controller;
use Api\Models\Users;
use Api\Models\Posts;

class adminController extends Controller
{

	//Exibe todas as categorias cadastradas
	public function index() {
		$array = array('error'=> '', 'logged'=> false);

		$method = $this->get_method();
		$dados = $this->get_request_dados();

		$user = new Users();
		$posts = new Posts();

		//Verifica se o jwt foi enviado e valida ele
		if(!empty($dados['jwt']) && $user->validate_jWT($dados['jwt'])) {
			$array['logged'] = true;

			if($method == 'GET') {
				$array['posts'] = $posts->all_posts();
			} else {
				$array['error'] = 'Metodo indisponivel!';
			}
		} else {
			$array['error'] = 'Acesso negado!';
		}

		$this->return_json($array);
	}
}