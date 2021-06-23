<?php
namespace Api\Controllers;

use Api\Core\Controller;
use Api\Models\Users;


class categoriesController extends Controller
{
	public function index()
	{
		
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

			switch($method) {
				case 'GET':

					break;
				case 'PUT':

					break;
				case 'DELETE':

					break;
				default:
					$array['error'] = 'Método '.$method.' indisponível';
					break;
			}
		} else {
			$array['error'] = 'Acesso negado';
		}

		$this->return_json($array);
	}
}