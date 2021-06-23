<?php

namespace Api\Core;

use Api\Helper\Route;

class Core extends Route
{

	public function run() {
		
		$url = '/';
		//Pega a url enviado pelo user
		if(isset($_GET['url'])) {
			$url.= $_GET['url'];//Concatena o dominio com o que foi digitado pelo user
		}

		//Vai fazer a transformação baseada na route
		$url = $this->checkRoutes($url);

		$params = array();
		//Verificando Se a pessoa enviou alguma url
		if(!empty($url) && $url != '/') {
			//Caso ele tenha enviado um endereço especifico do site
			$url = explode('/', $url);
			//Remove o primeiro registro do array
			array_shift($url);
			
			$currentControler = $url[0].'Controller';//fica (homeController)
			array_shift($url);//remove novamente

			//Verificando se o user enviou o action pela url
			if(isset($url[0]) &&  !empty($url[0])) {
				$currentAction = $url[0];
				array_shift($url);
			} else {

				$currentAction = 'index';
			}
			//Parametros
			if(count($url) > 0) {
				$params = $url;
			}

		} else {
			//Controller e action padrao
			$currentControler = 'HomeController';
			$currentAction = 'index';
		}

		//Transformando a primeira letra em Maiuscula
		$currentControler = ucfirst($currentControler);

		$prefix = 'Api\Controllers\\';
		
		//Verifica se o controller existe
		if(!file_exists('Api/Controllers/'.$currentControler.'.php') ||
			!method_exists($prefix.$currentControler, $currentAction)) {
			//Substitui pelo nosso controller padrão para páginas não encontradas
			$currentControler = 'NotfoundController';
			$currentAction = 'index';
		}

		//Instancia o controller
		$newController = $prefix.$currentControler;
		$c = new $newController();

		//Instancia a action
		call_user_func_array(array($c, $currentAction), $params);
	}

	public function checkRoutes($url) {

		foreach($this->configRouter() as $pt => $newurl) {

			// Identifica os argumentos e substitui por regex
			$pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $pt);

			// Faz o match da URL
			if(preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1) {
				array_shift($matches);
				array_shift($matches);

				// Pega todos os argumentos para associar
				$itens = array();
				if(preg_match_all('(\{[a-z0-9]{1,}\})', $pt, $m)) {
					$itens = preg_replace('(\{|\})', '', $m[0]);
				}

				// Faz a associação
				$arg = array();
				foreach($matches as $key => $match) {
					$arg[$itens[$key]] = $match;
				}

				// Monta a nova url
				foreach($arg as $argkey => $argvalue) {
					$newurl = str_replace(':'.$argkey, $argvalue, $newurl);
				}

				$url = $newurl;
				
				break;
			}
		}
		return $url;
	}
}