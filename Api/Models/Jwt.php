<?php
namespace Api\Models;

use Api\Core\Model;

class Jwt extends Model {

	private $secret;

	public function __construct() {
		$this->secret = "ij35h@";
	}

	//Cria e retorna o hash
	public function create($dados) {
		$header = json_encode(array("typ"=>"JWT", "alg"=>"HS256"));

		$payload = json_encode($dados);

		/*Fazendo a transformação do json do header e do json payload para BASE64.Queremos o bese64url*/
		$headerBase = $this->base64url_encode($header);
		$payloadBase = $this->base64url_encode($payload);

		//Gerar a assinatura
		$signature = hash_hmac("sha256", $headerBase.".".$payloadBase, $this->secret, true);
		$signatureBase = $this->base64url_encode($signature);

		//Juntando os 3 códigos do header, payload e signature
		$jwt = $headerBase.".".$payloadBase.".".$signatureBase;

		return $jwt;
	}

	public function validate($token) {
		/*
		* Processos de validação de token minimos:
		* 1-passo 1: Verificar se o TOKEN tem 3 partes
		* 2-passo 2: As assinaturas tem de ser igual os dados
		*/
		$array = array();

		$jwt_split = explode('.', $token);
		
		//Verifica se tem 3 partes
		if(count($jwt_split) == 3) {
			/*vamos criar uma nova assinatura baseados nos 2 primeiros itens do nosso token*/
			//Código da criação da assinatura(signature)
			$signature = hash_hmac("sha256", $jwt_split[0].".".$jwt_split[1], $this->secret, true);
			$signatureBase = $this->base64url_encode($signature);

			//Verificando se assinatura é a mesma
			if($signatureBase == $jwt_split[2]) {
				//Vamos pegar as informações do payload
				$array = json_decode($this->base64url_decode($jwt_split[1]));
				//Retorna um array
				return $array;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
	//Essas duas funções peguei na Documentação do PHP
	private function base64url_encode($dados) {
	  return rtrim(strtr(base64_encode($dados), '+/', '-_'), '=');
	}

	private function base64url_decode($dados) {
	  return base64_decode(strtr($dados, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen($dados)) % 4 ));
	}
}