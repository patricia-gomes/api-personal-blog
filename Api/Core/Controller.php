<?php
namespace Api\Core;

class Controller {

	//Para identificar o metódo usado na requisição
	public function get_method() {
		return $_SERVER['REQUEST_METHOD'];
	}

	//Para identificar os metodos e dar um retorno especifico para cada um deles
	public function get_request_dados() {

		switch($this->get_method()) {
			case 'GET':
				return $_GET;
				break;
			case 'PUT':
			case 'DELETE':
				parse_str(file_get_contents('php://input'), $data);//Retorna em array de objetos
				return (array) $data;//Converte de array de obj para array

				break;
			case 'POST':
				$data = json_decode(file_get_contents('php://input'));//Retorna em json

				//Proteção basica
				if(is_null($data)) {
					$data = $_POST;
				}

				return (array) $data;
				break;
		}
	}

	//Retorno em json
	public function return_json($array) {
		//Seta o retorno como json
		header("Content-Type: application/json");

		echo json_encode($array);
		exit;
	}
}